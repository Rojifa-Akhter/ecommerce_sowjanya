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
use Illuminate\Support\Facades\Notification;

class ProductController extends Controller
{
    // Add Category
    public function categoryAdd(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'name' => $validated['name'],
        ]);

        return response()->json(['message' => 'Category added successfully', 'category' => $category], 200);
    }

    // List All Categories
    public function categoryList()
    {
        $categories = Category::all();
        return response()->json(['categories' => $categories], 200);
    }

    // Update Category
    public function categoryUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $validated['name'],
        ]);

        return response()->json(['message' => 'Category updated successfully', 'category' => $category], 200);
    }

    // Delete Category
    public function categoryDelete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
    // Add Product
    public function productAdd(Request $request)
    {
        $validated = $request->validate([
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

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product_images', 'public');
                $imagePaths[] = asset('storage/' . $path);
            }
        }

        $colors = $request->color ? json_encode($request->color) : null;

        $stockStatus = 'In Stock'; //stock status
        if ($validated['quantity'] < 1) {
            $stockStatus = 'Out of Stock';
        } elseif ($validated['quantity'] <= 5) {
            $stockStatus = 'Low Stock';
        }
        $product = Product::updateOrCreate(
            ['title' => $validated['title']],
            [
                'category' => $validated['category'] ?? null,
                'brand' => $validated['brand'] ?? null,
                'image' => $imagePaths ?: [],
                'price' => $validated['price'],
                'quantity' => $validated['quantity'],
                'sale_price' => $validated['sale_price'],
                'SKU' => $validated['SKU'] ?? null,
                'stock' => $stockStatus,
                'tags' => $request->tags ?? null,
                'color' => $colors,
                'size' => $request->size ?? null,
                'description' => $validated['description'],
            ]
        );
        $product->color = json_decode($product->color);
        // Send notification
        $message = $product->wasRecentlyCreated ? 'Product added successfully' : 'Product updated successfully';
        if ($product->wasRecentlyCreated) {
            Notification::send(User::all(), new ProductAddedNotification($product));
        }

        return response()->json([
            'message' => $message,
            'product' => $product,
        ], 200);
    }

    // List All Products
    public function productList(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        if ($perPage <= 0) {
            return response()->json(['message' => "'per_page' must be a positive number."], 400);
        }

        $search = $request->input('search');
        $filter = $request->input('filter');

        $productsQuery = Product::query();

        // Search logic
        if ($search) {
            $productsQuery->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('stock', 'LIKE', "%{$search}%");
            });
        }

        // Filter logic
        if ($filter === 'title') {
            $productsQuery->orderBy('title', 'asc');
        } elseif ($filter === 'stock') {
            $productsQuery->orderBy('stock', 'desc');
        }

        $products = $productsQuery->select('title', 'image', 'price', 'quantity', 'no_of_sale', 'stock')
            ->paginate($perPage);

        $defaultImage = asset('img/default-product.webp');

        $products->getCollection()->transform(function ($product) use ($defaultImage) {
            $product->image = $product->image ?: $defaultImage;
            return $product;
        });

        return response()->json(['products' => $products], 200);
    }

    // Update Product
    public function productUpdate(Request $request, $id)
    {
        $validated = $request->validate([
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
            'images' => 'max:5',
        ]);

        $product = Product::findOrFail($id);

        if ($request->has('title')) {
            $product->title = $validated['title'];
        }
        if ($request->has('category')) {
            $product->category = $validated['category'];
        }
        if ($request->has('brand')) {
            $product->brand = $validated['brand'];
        }
        if ($request->has('description')) {
            $product->description = $validated['description'];
        }
        if ($request->has('price')) {
            $product->price = $validated['price'];
        }
        if ($request->has('sale_price')) {
            $product->sale_price = $validated['sale_price'];
        }
        if ($request->has('color')) {
            $product->color = $validated['color'];
        }
        $imagePaths = [];
        if ($request->hasFile('images')) {
            if (count($request->file('images')) > 5) {
                return response()->json(['error' => 'You can upload a maximum of 5 images.'], 400);
            }

            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('product_images', 'public');
                    $imagePaths[] = asset('storage/' . $path);
                } else {
                    return response()->json(['error' => 'One or more images failed to upload.'], 400);
                }
            }

            $product->image = $imagePaths; // Store as an array (no need to json_encode)
        }

        $product->save();

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product,
        ], 200);
    }

    // Delete Product
    public function productDelete($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }

    //blog
    public function blogAdd(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'images' => 'max:5',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('blog_images', 'public');
                $imagePaths[] = asset('storage/' . $path);
            }
        }

        $blog = Blog::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'date' => $validated['date'],
            'image' => $imagePaths,
        ]);

        return response()->json([
            'message' => 'Blog added successfully',
            'blog' => $blog,
        ], 200);
    }
// Update Product
    public function blogUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'images' => 'max:5',
        ]);

        $blog = Blog::findOrFail($id);

        if ($request->has('title')) {
            $blog->title = $validated['title'];
        }
        if ($request->has('description')) {
            $blog->description = $validated['description'];
        }
        if ($request->has('date')) {
            $blog->date = $validated['date'];
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            if (count($request->file('images')) > 5) {
                return response()->json(['error' => 'You can upload a maximum of 5 images.'], 400);
            }

            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('blog_images', 'public');
                    $imagePaths[] = asset('storage/' . $path);
                } else {
                    return response()->json(['error' => 'One or more images failed to upload.'], 400);
                }
            }

            $blog->image = json_encode($imagePaths);
        }

        $blog->save();

        $blog->image = json_decode($blog->$image);

        return response()->json([
            'message' => 'Blog updated successfully',
            'blog' => $blog,
        ], 200);
    }
    public function blogList()
    {
        $blogs = Blog::all();
        return response()->json(['blog' => $blogs], 200);
    }

    public function blogDelete($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully'], 200);
    }
    //about us
    public function aboutAdd(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'images' => 'max:5',
        ]);
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('about_images', 'public');
                $imagePaths[] = asset('storage/' . $path);
            }
        }
        $about = About::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $imagePaths,

        ]);

        return response()->json(['message' => 'About added successfully', 'about' => $about], 200);
    }
    public function aboutUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $about = About::findOrFail($id);

        if ($request->has('title')) {
            $about->title = $validated['title'];
        }

        if ($request->has('description')) {
            $about->description = $validated['description'];
        }

        // Handle images
        $existingImages = is_string($about->image) ? json_decode($about->image, true) : ($about->image ?? []);
        $imagePaths = $existingImages; // existing images

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('about_images', 'public');
                $imagePaths[] = asset('storage/' . $path);
            }
        }

        $about->image = json_encode($imagePaths);
        $about->save();

        return response()->json([
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
        $about->delete();

        return response()->json(['message' => 'About deleted successfully'], 200);
    }
    //faq add, update delete
    public function faqAdd(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        $faq = FAQ::create([
            'question' => $validated['question'],
            'answer' => $validated['answer'],

        ]);

        return response()->json(['message' => 'FAQ added successfully', 'faq' => $faq], 200);
    }
    public function faqUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'question' => 'nullable|string|max:255',
            'answer' => 'nullable|string',
        ]);

        $faq = FAQ::findOrFail($id);

        if ($request->has('question')) {
            $faq->question = $validated['question'];
        }

        if ($request->has('answer')) {
            $faq->answer = $validated['answer'];
        }

        $faq->save();

        return response()->json([
            'message' => 'FAQ updated successfully',
            'faq' => [
                'id' => $faq->id,
                'question' => $faq->question,
                'answer' => $faq->answer,
                'updated_at' => $faq->updated_at,
            ],
        ], 200);
    }
    public function faqDelete($id)
    {
        $faq = FAQ::findOrFail($id);
        $faq->delete();

        return response()->json(['message' => 'FAQ deleted successfully'], 200);
    }
}
