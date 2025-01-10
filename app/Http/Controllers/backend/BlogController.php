<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    //blog
    public function blogAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $blog_image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            if (is_array($image)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only one image can be uploaded.',
                ], 400);
            }

            if ($image->isValid()) {
                $extension = $image->getClientOriginalExtension();
                $blog_image = time() . uniqid() . '.' . $extension;
                $image->move(public_path('uploads/blog_images'), $blog_image);
            }
        }

        $blog = Blog::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $blog_image,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Blog added successfully.',
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
            'image' => 'nullable|',
        ])->validated();

        $blog->title = $validated['title'] ?? $blog->title;
        $blog->description = $validated['description'] ?? $blog->description;

        if ($request->hasFile('image')) {
            $existingImage = $blog->image;

            if ($existingImage) {
                $oldImage = parse_url($existingImage);
                $filePath = ltrim($oldImage['path'], '/');
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Upload new image
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $newName = time() . '.' . $extension;
            $image->move(public_path('uploads/blog_images'), $newName);

            $blog->image = $newName;
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

        // $defaultImage = asset('img/1.webp');
        $defaultImage = url(Storage::url('profile_images/default_user.png'));

        $perPage = $request->query('per_page', 10);
        $blogs = Blog::paginate($perPage);

        // $blogs->getCollection()->transform(function ($blog) use ($defaultImage) {
        //     $blog->image = $blog->image ?? $defaultImage;
        //     return $blog;
        // });

        return response()->json([
            'status' => 'success',
            'blogs' => $blogs,
        ], 200);
    }
    //single blog
    public function blogDetails($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'status' => 'error',
                'message' => 'Blog not found',
            ], 404);
        }
        $blogDetails = [
            'image' => $blog->image ?? asset('img/1.webp'),
            'title' => $blog->title,
            'date' => $blog->date,
            'description' => $blog->description,
        ];

        return response()->json([
            'status' => 'success',
            'data' => $blogDetails,
        ]);
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
}
