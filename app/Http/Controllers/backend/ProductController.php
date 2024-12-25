<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Blog;
use App\Models\Category;
use App\Models\FAQ;
use App\Models\Product;
use App\Models\User;
use App\Notifications\ProductAddedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // Add Category
    public function categoryAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category added successfully',
            'category' => $category,
        ], 200);
    }

    // List All Categories
    public function categoryList()
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No categories found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'categories' => $categories,
        ], 200);
    }

    // Update Category
    public function categoryUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found.',
            ], 404);
        }

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category updated successfully',
            'category' => $category,
        ], 200);
    }

    // Delete Category
    public function categoryDelete($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found.',
            ], 404);
        }

        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully',
        ], 200);
    }
    // Add Product
    public function productAdd(Request $request)
    {
        // Validation
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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product_images', 'public');
                $imagePaths[] = asset('storage/' . $path);
            }
        }

        $colors = $request->color ? json_encode($request->color) : null;

        // Stock status
        $stockStatus = 'In Stock';
        if ($request->quantity < 1) {
            $stockStatus = 'Out of Stock';
        } elseif ($request->quantity <= 5) {
            $stockStatus = 'Low Stock';
        }

        $product = Product::updateOrCreate(
            ['title' => $request->title], // Check if product exists by title
            [
                'category' => $request->category ?? null,
                'brand' => $request->brand ?? null,
                'image' => $imagePaths ?: [], // Store images as an array
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

        // Send notification
        $message = $product->wasRecentlyCreated ? 'Product added successfully' : 'Product updated successfully';
        if ($product->wasRecentlyCreated) {
            Notification::send(User::all(), new ProductAddedNotification($product));
        }
        // Return response
        return response()->json([
            'status' => 'success',
            'message' => $message,
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

        $products = $productsQuery->select('title', 'image', 'price', 'quantity', 'no_of_sale', 'stock')
            ->paginate($perPage);

        $defaultImage = asset('img/1.webp');

        $products->getCollection()->transform(function ($product) use ($defaultImage) {
            $product->image = $product->image ?: $defaultImage;
            return $product;
        });

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
            'SKU' => 'nullable|numeric',
            'stock' => 'nullable|numeric',
            'tags' => 'nullable|numeric',
            'color' => 'nullable|array',
            'size' => 'nullable|numeric',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'images' => 'nullable|array|max:5',
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
        $product->stock = $validatedData['stock'] ?? $product->stock;
        $product->tags = $validatedData['tags'] ?? $product->tags;
        $product->color = $validatedData['color'] ?? $product->color;
        $product->size = $validatedData['size'] ?? $product->size;

        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('product_images', 'public');
                    $imagePaths[] = asset('storage/' . $path);

                }
            }
            $product->image = $imagePaths;
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

    //blog
    public function blogAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('image') && is_array($request->file('image'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Only one image can be uploaded.',
            ], 400);
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blog_images', 'public');
            $imagePath = asset('storage/' . $path);
        }

        $blog = Blog::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'date' => $request->input('date'),
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Blog added successfully',
            'blog' => $blog,
        ], 200);
    }

// Update Product
    public function blogUpdate(Request $request, $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['status' => 'error', 'message' => 'Blog not found'], 404);
        }

        $validated = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ])->validated();

        $blog->title = $validated['title'] ?? $blog->title;
        $blog->description = $validated['description'] ?? $blog->description;
        $blog->date = $validated['date'] ?? $blog->date;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blog_images', 'public');
            $blog->image = asset('storage/' . $path);
        }

        $blog->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Blog updated successfully',
            'blog' => $blog,
        ], 200);
    }

    public function blogList(Request $request)
    {
        $user = Auth::user();

        if (!$user) {return response()->json(['status' => 'error', 'message' => 'User not authenticated.'], 401);}

        $defaultImage = asset('img/1.webp');

        $perPage = $request->query('per_page', 10);
        $blogs = Blog::paginate($perPage);

        $blogs->getCollection()->transform(function ($blog) use ($defaultImage) {
            $blog->image = $blog->image ?? $defaultImage;
            return $blog;
        });

        return response()->json([
            'status' => 'success',
            'blogs' => $blogs,
        ], 200);
    }

    public function blogDelete($id)
    {
        $blog = Blog::findOrFail($id);
        if (!$blog) {return response()->json(['status' => 'error', 'message' => 'Blog Not Found'], 200);}

        $blog->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Blog deleted successfully'], 200);
    }
    //about us
    public function aboutAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'images' => 'nullable|array|max:5', // Max 5 images
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('about_images', 'public');
                $imagePaths[] = asset('storage/' . $path);
            }
        }

        $about = About::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $imagePaths,

        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'About added successfully',
            'about' => $about,
        ], 200);
    }

    public function aboutUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $about = About::find($id);

        if (!$about) {
            return response()->json([
                'status' => 'error',
                'message' => 'About entry not found',
            ], 404);
        }

        $about->title = $request->input('title', $about->title);
        $about->description = $request->input('description', $about->description);

        $imagePaths = is_array($about->image) ? $about->image : [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('about_images', 'public');
                $imagePaths[] = asset('storage/' . $path);
            }
        }

        $about->image = $imagePaths;
        $about->save();

        return response()->json([
            'status' => 'success',
            'message' => 'About updated successfully',
            'about' => [
                'id' => $about->id,
                'title' => $about->title,
                'description' => $about->description,
                'images' => $imagePaths,
                'updated_at' => $about->updated_at,
            ],
        ], 200);
    }

    public function aboutDelete($id)
    {
        $about = About::findOrFail($id);
        if (!$about) {return response()->json(['status' => 'error', 'message' => 'About Not Found'], 200);}

        $about->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'About deleted successfully'], 200);
    }
    //faq add, update delete
    public function faqAdd(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);
        if ($validated->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation failed.', 'errors' => $validated->errors()], 422);
        }

        $faq = FAQ::create([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'FAQ added successfully', 'faq' => $faq], 200);
    }
    public function faqUpdate(Request $request, $id)
    {
        $request->validate([
            'question' => 'nullable|string|max:255',
            'answer' => 'nullable|string',
        ]);
    
        $faq = FAQ::find($id);
    
        if (!$faq) {
            return response()->json(['status' => 'error','message' => 'FAQ not found', ], 404); }
    
        $faq->question = $request->question ?? $faq->question;
        $faq->answer = $request->answer ?? $faq->answer;
    
        $faq->save();
    
        return response()->json([
            'status' => 'success',
            'message' => 'FAQ updated successfully.',
            'faq' => $faq,
        ], 200);
    }
    
    public function faqDelete($id)
    {
        $faq = FAQ::findOrFail($id);
        if (!$faq) {return response()->json(['status' => 'error', 'message' => 'FAQ Not Found'], 200);}
        $faq->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'FAQ deleted successfully'], 200);
    }
}
