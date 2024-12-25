<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\OrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // Validate incoming request data
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id', // Check if the product exists
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'contact' => 'required|string|max:15',
            'payment_method_id' => 'required|string', // Payment method ID from frontend
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Find the product
        $product = Product::find($validated['product_id']);

        // If product doesn't exist, return an error
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Set the Stripe secret API key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Create a PaymentIntent with Stripe
            $paymentIntent = PaymentIntent::create([
                'amount' => $product->price * 100, // Amount in cents
                'currency' => 'usd',
                'payment_method' => $validated['payment_method_id'],
                'confirm' => false,
                'confirmation_method' => 'manual',
            ]);

            // Create a new order in the database
            $order = Order::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'amount' => $product->price,
                'status' => 'pending',
                'street_address' => $validated['street_address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'zip_code' => $validated['zip_code'],
                'contact' => $validated['contact'],
                'transaction_id' => $paymentIntent->id,
            ]);

            // Get the admin(s) and send notification
            $admins = User::where('role', 'ADMIN')->get(); // Assuming there's a role column for admins
            foreach ($admins as $admin) {
                $admin->notify(new OrderPlaced($order)); // Send notification to the admin
            }

            // Return the success response
            return response()->json([
                'message' => 'Payment Intent created successfully',
                'client_secret' => $paymentIntent->client_secret,
                'order' => $order,
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions
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
