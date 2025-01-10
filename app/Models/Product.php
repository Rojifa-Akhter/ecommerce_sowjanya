<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'images' => 'array',
    ];

    public function getImageAttribute($image)
{
    // Decode the JSON-encoded images
    $images = json_decode($image, true);

    // Ensure $images is an array
    if (!is_array($images) || empty($images)) {
        return [asset('uploads/product_images/default_image.jpg')];
    }

    // Map the image URLs
    return array_map(fn($img) => asset('uploads/product_images/' . $img), $images);
}

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

}
