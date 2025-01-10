<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\ProductAddedMail;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Notifications\ProductAddedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // Add Product
    public function productAdd(Request $request)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'SKU' => 'nullable|string',
            'color' => 'nullable|array',
            'quantity' => 'required|integer|min:0',
            // 'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }

        $images = [];

        if ($request->hasFile('image') && is_array($request->file('image'))) {
            foreach ($request->file('image') as $file) {
                if ($file->isValid()) {
                    $extension = $file->getClientOriginalExtension();
                    $aboutImage = time() . uniqid() . '.' . $extension;
                    $file->move(public_path('uploads/product_images'), $aboutImage);
                    $images[] = $aboutImage;
                }
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'No valid files uploaded.'], 400);
        }
// return $images;

        $colors = $request->color ? json_encode($request->color) : null;

        // Stock status
        $stockStatus = 'In Stock';
        if ($request->quantity < 1) {
            $stockStatus = 'Out of Stock';
        } elseif ($request->quantity <= 5) {
            $stockStatus = 'Low Stock';
        }

        $product = Product::create(
            ['title' => $request->title,
                'image' => json_encode($images),
                'price' => $request->price,
                'quantity' => $request->quantity,
                'sale_price' => $request->sale_price,
                'SKU' => $request->SKU ?? null,
                'stock' => $stockStatus,
                'tags' => $request->tags ?? null,
                'color' => $colors,
                'size' => $request->size ?? null,
                'description' => $request->description,
            ]
        );

        $product->color = json_decode($product->color);

        $active_users = User::where('role', 'USER')->where('status', 'active')->get();
        foreach ($active_users as $user) {
            Mail::to($user->email)->send(new ProductAddedMail($product));
        }

        if ($product->wasRecentlyCreated) {
            Notification::send($active_users, new ProductAddedNotification($product));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product Added Successfully',
            'product' => $product,
        ], 200);
    }

    // List All Products
    public function productList(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        if ($perPage <= 0) {
            return response()->json(['status' => 'error', 'message' => "'per_page' must be a positive number."], 400);
        }

        $search = $request->input('search');
        $filter = $request->input('filter');

        $productsQuery = Product::query();

        if ($search) {
            $productsQuery->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('stock', 'LIKE', "%{$search}%");
            });
        }

        if ($filter === 'title') {
            $productsQuery->orderBy('title', 'asc');
        } elseif ($filter === 'stock') {
            $productsQuery->orderBy('stock', 'desc');
        } elseif ($filter) {
            return response()->json(['status' => 'error', 'message' => "Invalid filter value."], 400);
        }

        $products = $productsQuery->select('id', 'title', 'image', 'price', 'quantity', 'description', 'no_of_sale', 'stock')
            ->paginate($perPage);

        // $defaultImage = url(Storage::url('product_images/default_image.jpg'));
        // $products->getCollection()->transform(function ($product) use ($defaultImage) {
        //     $product->image = $product->image ?? $defaultImage;
        //     // return $product;
        // });

        return response()->json([
            'status' => 'success',
            'products' => $products,
        ], 200);
    }

    // Update Product
    public function productUpdate(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'SKU' => 'nullable|string',
            'quantity' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
            'color' => 'nullable|array',
            'size' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // Allow image files
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validatedData = $validator->validated();

        $product->title = $validatedData['title'] ?? $product->title;
        $product->category = $validatedData['category'] ?? $product->category;
        $product->brand = $validatedData['brand'] ?? $product->brand;
        $product->description = $validatedData['description'] ?? $product->description;
        $product->price = $validatedData['price'] ?? $product->price;
        $product->sale_price = $validatedData['sale_price'] ?? $product->sale_price;
        $product->SKU = $validatedData['SKU'] ?? $product->SKU;
        $product->tags = $validatedData['tags'] ?? $product->tags;
        $product->color = $validatedData['color'] ?? $product->color;
        $product->size = $validatedData['size'] ?? $product->size;

        if (isset($validatedData['quantity'])) {
            $product->quantity = $validatedData['quantity'];
            if ($product->quantity < 1) {
                $product->stock = 'Out of Stock';
            } elseif ($product->quantity <= 5) {
                $product->stock = 'Low Stock';
            } else {
                $product->stock = 'In Stock';
            }
        }

        // Handling image upload
        if ($request->has('images')) {
            $images = [];

            // Delete old images if new ones are uploaded
            $existingImages = is_array($product->image)
                ? $product->image
                : json_decode($product->image, true) ?? [];

                foreach ($existingImages as $oldImage) {
                    $parsedUrl = parse_url($oldImage);
                    $filePath = ltrim($parsedUrl['path'], '/');
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }

            // Process new images
            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $extension = $file->getClientOriginalExtension();
                    $productImage = time() . uniqid() . '.' . $extension;
                    $file->move(public_path('uploads/product_images'), $productImage);
                    $images[] = $productImage;
                }
            }

            $product->image = json_encode($images);
        }

        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully',
            'product' => $product,
        ], 200);
    }

    // Delete Product
    public function productDelete($id)
    {
        $product = Product::findOrFail($id);
        if (!$product) {return response()->json(['status' => 'error', 'message' => 'Product Not Found'], 200);}
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully'], 200);
    }

}
