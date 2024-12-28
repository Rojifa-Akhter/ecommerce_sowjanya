<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\OrderPlaced;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Stripe\PaymentIntent;
use Stripe\Stripe;

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
            'payment_status' => 'required|in:success,failure', // Added payment status validation

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

            $adminUsers = User::where('role', 'admin')->get();

            // Send notifications to each admin
            foreach ($adminUsers as $adminUser) {
                $adminUser->notify(new OrderPlaced($order));
            }

            return response()->json([
                'status' => true,
                'message' => 'Payment recorded successfully and notification sent to admins.',
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

    public function cancel()
    {
        return response()->json(['message' => 'Payment was canceled.'], 400);
    }

    public function getAdminNotifications()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access. Only admins can view notifications.',
            ], 403);
        }

        // Check if the user has notifications
        $notifications = $user->notifications()->get();

        if ($notifications->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No notifications available.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'notifications' => $notifications,
        ], 200);
    }

    public function markNotification($notificationId)
    {
        $user = Auth::user();

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

}
