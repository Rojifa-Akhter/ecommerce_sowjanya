<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    // Validate the incoming request
    $validator = Validator::make($request->all(), [
        'amount' => 'required|numeric|min:1',
        'payment_method' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation Error',
            'errors' => $validator->errors(),
        ], 422);
    }

    try {
        // Set the Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Create a PaymentIntent with manual confirmation
        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount * 100, // Amount in cents
            'currency' => 'usd',
            'payment_method' => $request->payment_method, // Payment method ID
            'confirmation_method' => 'manual', // Set manual confirmation
            'confirm' => false, // Do not confirm automatically
        ]);

        // Return success response with PaymentIntent data
        return response()->json([
            'status' => 'success',
            'message' => 'Payment Intent created successfully.',
            'data' => $paymentIntent,
        ], 200);
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // Return error response for Stripe API errors
        return response()->json([
            'status' => 'error',
            'message' => 'Payment failed.',
            'error' => $e->getMessage(),
        ], 500);
    } catch (\Exception $e) {
        // Return error response for general exceptions
        return response()->json([
            'status' => 'error',
            'message' => 'An unexpected error occurred.',
            'error' => $e->getMessage(),
        ], 500);
    }
}



    public function success(Request $request)
    {
        if (!$request->has('session_id')) {
            return response()->json(['message' => 'Invalid session'], 400);
        }

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        try {
            $session = $stripe->checkout->sessions->retrieve($request->session_id);
            $customerDetails = $session->customer_details;

            // Save the payment details
            $payment = new Order();
            $payment->payment_id = $session->id;
            $payment->product_id = session()->get('product_id');
            $payment->quantity = session()->get('quantity');
            $payment->amount = $session->amount_total / 100; // Convert cents to dollars
            $payment->currency = $session->currency;
            $payment->customer_name = $customerDetails->name;
            $payment->customer_email = $customerDetails->email;
            $payment->payment_status = $session->payment_status;
            $payment->payment_method = 'Stripe';
            $payment->save();

            session()->forget('product_id');
            session()->forget('quantity');

            return response()->json(['message' => 'Payment successful', 'payment' => $payment], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving payment details', 'error' => $e->getMessage()], 500);
        }
    }
    public function cancel()
    {
        return response()->json(['message' => 'Payment was canceled.'], 400);
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
