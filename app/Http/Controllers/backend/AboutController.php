<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AboutController extends Controller
{
    public function aboutAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'images' => 'nullable|array|max:3', // Allow a maximum of 3 images
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $about = About::first(); // Fetch the first record

        $newImages = [];
        if ($request->hasFile('images')) {
            // Store new images
            foreach ($request->file('images') as $image) {
                $path = $image->store('about_images', 'public');
                $newImages[] = asset('storage/' . $path);
            }
        }

        if ($about) {
            // Update the existing record
            $about->update([
                'title' => $request->title,
                'description' => $request->description,
                'image' => json_encode($newImages ?: json_decode($about->image, true)), // Replace images only if new ones are uploaded
            ]);
        } else {
            // Create a new record
            $about = About::create([
                'title' => $request->title,
                'description' => $request->description,
                'image' => json_encode($newImages),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => $about->wasRecentlyCreated ? 'About created successfully' : 'About updated successfully',
            'about' => $about,
        ], 200);
    }
}
