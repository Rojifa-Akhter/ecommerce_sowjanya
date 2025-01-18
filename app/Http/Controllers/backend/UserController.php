<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

        return response()->json([
            'status' => 'success',
            'notifications' => $notifications], 200);
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

        return response()->json([
            'status' => 'success',
            'message' => 'Notification marked as read.'], 200);
    }
    public function createReview(Request $request)
    {
        // Validate the request data
        $validated = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'comment' => 'nullable|string|max:500',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validated->errors(),
            ], 422);
        }

        $userId = Auth::id();
        $productId = $validated->validated()['product_id'];

        // Check if a review already exists for this user and product
        $existingReview = Review::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existingReview) {
            // Delete the old review
            $existingReview->delete();
        }

        // Create a new review
        $review = Review::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'comment' => $validated->validated()['comment'] ?? null,
            'rating' => $validated->validated()['rating'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Review added successfully. Previous review (if any) has been replaced.',
            'review' => $review,
        ], 201);
    }

    public function reviewList(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $productId = $request->query('product_id');

        if (!$productId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product ID is required.',
            ], 400);
        }

        $reviews = Review::with(['user:id,name,image', 'product:id,title'])
            ->where('product_id', $productId)
            ->paginate($perPage);

        $product = Product::withCount('reviews')
            ->withSum('reviews', 'rating')
            ->find($productId);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found.',
            ], 404);
        }

        $averageRating = $product->reviews_count > 0
        ? $product->reviews_sum_rating / $product->reviews_count
        : 0;

        $product->average_rating = min($averageRating, 5);

        $defaultUserImage = url(Storage::url('profile_images/default_user.png'));

        $reviews->getCollection()->transform(function ($review) use ($defaultUserImage, $product) {
            return [
                'user_name' => $review->user->name,
                'user_image' => $review->user->image ?? $defaultUserImage,
                'rating' => $review->rating,
                'comment' => $review->comment,
            ];
        });

        return response()->json([
            'status' => 'success',
            'reviews' => $reviews,
            'average_rating' => $product->average_rating,
            'reviews_count' => $product->reviews_count,
        ], 200);

    }

    //product view
    public function productView(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        if ($perPage <= 0) {
            return response()->json(['message' => "'per_page' must be a positive number."], 400);
        }

        $products = Product::where('title', 'LIKE', '%' . $request->search . '%')->orwhere('price', 'LIKE', '%' . $request->price . '%')->orwhere('no_of_sale', 'LIKE', '%' . $request->no_of_sale . '%')->withCount('reviews')
            ->withSum('reviews', 'rating')
            ->paginate($perPage);

        // $defaultImage = asset('img/default-product.webp');

        $products->getCollection()->transform(function ($product)  {
            // $product->image = $product->image ?: $defaultImage;

            // Calculate the average rating
            $averageRating = $product->reviews_count > 0
            ? $product->reviews_sum_rating / $product->reviews_count
            : 0;

            // Ensure the rating is capped at 5
            $product->average_rating = min($averageRating, 5);

            return [
                'id' => $product->id,
                'image' => $product->image,
                'title' => $product->title,
                'description' => $product->description,
                'price' => $product->price,
                'total_review' => $product->reviews_count,
                'rating' => $product->average_rating,
                'sale_price' => $product->sale_price,
                'quantity' => $product->quantity,
                'no_of_sale' => $product->no_of_sale,
            ];
        });

        return response()->json([
            'status' => 'success',
            'products' => $products], 200);
    }

    public function myOrder(Request $request)
    {

        $orders = Order::with('product.reviews')
            ->where('user_id', Auth::user()->id)
            ->get();

        $orderDetails = $orders->map(function ($order) {
            $rating = $order->product->reviews()->where('user_id', Auth::user()->id)->first();

            return [
                'image' => $order->product->image ?? asset('img/default-product.webp'),
                'product_id' => $order->product->id,
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
        // $user = Auth::user();
        $user=User::where('id',Auth::user()->id)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }
    public function aboutUs(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated.'], 401);
        }

        $defaultImage = asset('img/1.webp');

        $about = About::all();

        return response()->json([
            'status' => 'success',
            'about' => $about,
        ], 200);
    }

}
