<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
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
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'contact' => 'required|string|max:15',
            'payment_method_id' => 'required|string', // Payment method ID from frontend
        ]);
    
        $user = Auth::user();
        $product = Product::find($validated['product_id']);
    
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
 
        Stripe::setApiKey(env('STRIPE_SECRET'));
    
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $product->price * 100, // Amount in cents
                'currency' => 'usd',
                'payment_method' => $validated['payment_method_id'], 
                'confirm' => false, 
                'confirmation_method' => 'manual', 
            ]);
    
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
    
            return response()->json([
                'message' => 'Payment Intent created successfully',
                'client_secret' => $paymentIntent->client_secret,
                'order' => $order,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}
