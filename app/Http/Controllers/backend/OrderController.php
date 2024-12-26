<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\OrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class OrderController extends Controller
{
    public function showProduct($productId)
    {
        $product = Product::findOrFail($productId);
        return response()->json(['product' => $product], 200);
    }

    public function createOrder(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id', 
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'contact' => 'required|string|max:15',
            'payment_method_id' => 'required|string',
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 400);
        }

        $user = Auth::user();

        $product = Product::find($request->input('product_id'));

        if (!$product) 
        {
            return response()->json(['message' => 'Product not found'], 404);
        }


        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Create a PaymentIntent with Stripe
            $paymentIntent = PaymentIntent::create([
                'amount' => $product->price * 100, // Amount in cents
                'currency' => 'usd',
                'payment_method' => $request->input('payment_method_id'),
                'confirm' => false,
                'confirmation_method' => 'manual',
            ]);

            $order = Order::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'amount' => $product->price,
                'status' => 'pending',
                'street_address' => $request->input('street_address'),
                'city' => $request->input('city'),
                'state' => $request->input('state'),
                'zip_code' => $request->input('zip_code'),
                'contact' => $request->input('contact'),
                'transaction_id' => $paymentIntent->id,
            ]);

            // send notification
            $admins = User::where('role', 'ADMIN')->get(); 
            foreach ($admins as $admin) {
                $admin->notify(new OrderPlaced($order)); 
            }


            return response()->json([
                'message' => 'Payment Intent created successfully',
                'client_secret' => $paymentIntent->client_secret,
                'order' => $order,
            ], 200);
        } catch (\Exception $e) {
            
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getAdminNotifications()
    {
        $user = auth()->user();

        $notifications = $user->notifications()->get()->map(function ($notification) {
            return [
                'id' => $notification->id,
                'data' => $notification->data,
                'read_at' => $notification->read_at,
            ];
        });

        return response()->json([
            'status' => 'success',
            'notifications' => $notifications], 200);
    }
    public function markNotification($notificationId)
    {
        $owner = auth()->user();

        $notification = $owner->notifications()->find($notificationId);

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
