<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getNotifications()
    {
        $user = auth()->user();

        $notifications = $user->notifications()->get()->map(function ($notification) {
            return [
                'id' => $notification->id,
                'data' => $notification->data,
                'read_at' => $notification->read_at,
            ];
        });

        return response()->json(['notifications' => $notifications], 200);
    }
    public function markNotificationAsRead($notificationId)
    {
        $owner = auth()->user();

        $notification = $owner->notifications()->find($notificationId);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found.'], 404);
        }

        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        return response()->json(['message' => 'Notification marked as read.'], 200);
    }
    public function createReview(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'comment' => 'nullable|string|max:500',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $review = Review::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'comment' => $validated['comment'] ?? null,
            'rating' => $validated['rating'] ?? null,
        ]);

        return response()->json(['message' => 'Review added successfully', 'review' => $review], 201);
    }
    //product view
    public function productView(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        // Ensure that per_page is a positive number
        if ($perPage <= 0) {
            return response()->json(['message' => "'per_page' must be a positive number."], 400);
        }

        // Fetch products with review count and rating sum
        $products = Product::withCount('reviews') // Get the count of reviews
            ->withSum('reviews', 'rating') // Get the sum of ratings for the product
            ->paginate($perPage);

        $defaultImage = asset('img/default-product.webp'); // Default image path

        // Transforming the products collection
        $products->getCollection()->transform(function ($product) use ($defaultImage) {
            // Use default image if the product image is not available
            $product->image = $product->image ?: $defaultImage;

            // Calculate the average rating
            $averageRating = $product->reviews_count > 0
            ? $product->reviews_sum_rating / $product->reviews_count
            : 0;

            // Ensure the rating is capped at 5
            $product->average_rating = min($averageRating, 5);

            // Return the transformed data for each product
            return [
                'id' => $product->id,
                'image' => $product->image,
                'title' => $product->title,
                'description' => $product->description,
                'price' => $product->price,
                'total_review' => $product->reviews_count,
                'rating' => $product->average_rating,
            ];
        });

        // Return the paginated response with product data
        return response()->json(['products' => $products], 200);
    }

    // public function myprofile(Request $request)
    // {
    //     $orders = Order::with('product.reviews')->get();
    //     return $orders;

    //     $orderDetails = $orders->map(function ($order) {
    //         $averageRating = $order->product->reviews->avg('rating');

    //         return [
    //             'product_name' => $order->product->title ?? 'N/A',
    //             'price' => $order->product->price ?? 0,
    //             'status' => $order->status ?? 'Unknown',
    //             'rating' => $averageRating,
    //         ];
    //     });

    //     return response()->json(['Your order list' => $orderDetails], 200);
    // }

    public function myprofile(Request $request)
    {

        $orders = Order::with('product.reviews')
            ->where('user_id', Auth::user()->id)
            ->get();

        $orderDetails = $orders->map(function ($order) {
            $rating = $order->product->reviews()->where('user_id', Auth::user()->id)->first();

            return [
                'image' => $order->product->image ?? asset('img/default-product.webp'),
                'title' => $order->product->title,
                'price' => $order->product->price,
                'status' => $order->status,
                'rating' => $rating->rating ?? null,
            ];
        });
        return response()->json([
            'status' => 'success',
            'data' => $orderDetails,
        ]);
    }
    public function ownProfile(Request $request)
    {

        $user = User::select('id', 'name', 'image', 'email', 'role')->first();

        $user->image = $user->image ?? asset('img/1.webp');

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

}
