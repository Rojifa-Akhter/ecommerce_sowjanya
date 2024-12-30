<?php

namespace App\Http\Controllers\backend;

use Exception;
use Stripe\Stripe;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use App\Notifications\OrderPlaced;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function showProduct($productId)
    {
        $product = Product::withCount('reviews')
            ->withSum('reviews', 'rating')
            ->find($productId);

        $averageRating = $product->reviews_count > 0
        ? $product->reviews_sum_rating / $product->reviews_count
        : 0;

        // Cap the rating at 5
        $product->average_rating = min($averageRating, 5);

        return response()->json([
            'status' => true,
            'message' => 'data get sccessfully',
            'product' => [
                'id' => $product->id,
                'title' => $product->title,
                'category' => $product->category,
                'brand' => $product->brand,
                'image' => $product->image,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'quantity' => $product->quantity,
                'SKU' => $product->SKU,
                'stock' => $product->stock,
                'tags' => $product->tags,
                'color' => $product->color,
                'size' => $product->size,
                'description' => $product->description,
                'no_of_sale' => $product->no_of_sale,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
                'reviews_count' => $product->reviews_count,
                'reviews_sum_rating' => $product->reviews_sum_rating,
                'rating' => $product->average_rating,
            ],
        ]);

    }

    public function payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount * 100,
                'currency' => 'usd',
                'payment_method' => $request->payment_method,
                // 'confirmation_method' => 'manual',
                'confirm' => false,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Payment intent created successfully.',
                'data' => $paymentIntent,
            ], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Payment failed.',
            ], 200);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'transaction_id' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'street_address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'payment_status' => 'required|in:success,failure',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        try {
            $order = Order::create([
                'user_id' => $request->user_id,
                'product_id' => $request->product_id,
                'transaction_id' => $request->transaction_id,
                'amount' => $request->amount,
                'status' => $request->payment_status === 'success' ? 'delivered' : 'pending',
                'street_address' => $request->street_address,
                'city' => $request->city,
                'contact' => $request->contact,
            ]);

            // If payment is successful, update the product's sales count and quantity
            if ($request->payment_status === 'success') {
                $product = Product::find($request->product_id);
                if ($product) {
                    // Decrement the quantity by 1
                    if ($product->quantity > 0) {
                        $product->decrement('quantity');

                        $product->update(['no_of_sale' => 1]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Not enough stock available.',
                        ], 400);
                    }
                }
            }

            // Get product details for notification
            $product = Product::firstOrFail($request->product_id);
            $orderDate = $order->created_at->format('Y-m-d H:i:s');
            $address = $request->street_address . ', ' . $request->city . ', ' . $request->state;

            $adminUsers = User::where('role', 'ADMIN')->get();
            foreach ($adminUsers as $admin) {
                $admin->notify(new OrderPlaced($order, $product, $product->no_of_sale, $address, $orderDate));
            }

            return response()->json([
                'status' => true,
                'message' => 'Payment recorded successfully, notification sent to admins, and product sales updated.',
                'data' => $order,
            ], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to record payment.',
            ], 500);
        }
    }

    public function cancelOrder($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);

            // Check if the order is already delivered
            if ($order->status === 'delivered') {
                $product = Product::find($order->product_id);

                if ($product) {
                    // Decrease the sale count and increase the quantity (refund process)
                    $product->decrement('no_of_sale');
                    $product->increment('quantity');
                }

                // Update order status to 'canceled'
                $order->update(['status' => 'canceled']);

                return response()->json([
                    'status' => true,
                    'message' => 'Order canceled successfully, stock updated.',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Order cannot be canceled, it is not yet delivered.',
                ], 400);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to cancel the order.',
            ], 500);
        }
    }

    public function getAdminNotifications(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $user = Auth::user();

        if ($user->role !== 'ADMIN') {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access. Only admins can view notifications.',
            ], 403);
        }

        $notifications = $user->notifications()->paginate($perPage);
        $unread= DB::table('notifications')->where('notifiable_id',1)->whereNull('read_at')->count();

        if ($notifications->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No notifications available.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'unread_notification'=>$unread,
            'notifications' => $notifications,
        ], 200);
    }


    public function markNotification($notificationId)
    {
        $user = Auth::user();

        if ($user->role !== 'ADMIN') {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access. Only admins can view notifications.',
            ], 403);
        }

        $notification = $user->notifications()->find($notificationId);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found.'], 404);
        }

        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Notification marked as read.'], 200);
    }
    public function markAllNotification(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'ADMIN') {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access. Only admins can manage notifications.',
            ], 403);
        }

        $notifications = $user->unreadNotifications;

        if ($notifications->isEmpty()) {
            return response()->json(['message' => 'No unread notifications found.'], 404);
        }

        $notifications->markAsRead();

        return response()->json([
            'status' => 'success',
            'message' => 'All notifications marked as read.',
        ], 200);
    }

}
