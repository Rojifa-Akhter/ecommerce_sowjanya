<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AboutController extends Controller
{
    public function aboutAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|array|max:3', // Allow a maximum of 3 images
            // 'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // Validate each image file
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $about = About::first();

        $images = []; // Initialize the images array to avoid undefined variable errors

        if ($request->hasFile('image')) {
            // Delete existing images if they exist
            if ($about && $about->image) {
                $existingImages = is_array($about->image)
                ? $about->image
                : json_decode($about->image, true) ?? [];

                foreach ($existingImages as $oldImage) {
                    $oldImagePath = public_path('uploads/about_images/' . $oldImage);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath); // Delete old image
                    }
                }
            }

            // Handle new image uploads
            foreach ($request->file('image') as $file) { // Use $file to avoid variable name conflict
                if ($file->isValid()) {
                    $extension = $file->getClientOriginalExtension();
                    $aboutImage = time() . uniqid() . '.' . $extension;
                    $file->move(public_path('uploads/about_images'), $aboutImage);
                    $images[] = $aboutImage; // Add new image to the array
                }
            }
        }

        if ($about) {
            // Update the existing record
            $about->update([
                'title' => $request->title,
                'description' => $request->description,
                'image' => json_encode($images ?: json_decode($about->image, true)), // Replace images only if new ones are uploaded
            ]);
        } else {
            // Create a new record
            $about = About::create([
                'title' => $request->title,
                'description' => $request->description,
                'image' => json_encode($images), // Save the images
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => $about->wasRecentlyCreated ? 'About created successfully' : 'About updated successfully',
            'about' => $about,
        ], 200);
    }

    public function aboutList()
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
