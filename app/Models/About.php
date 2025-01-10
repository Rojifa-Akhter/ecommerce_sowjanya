<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'images' => 'array',
    ];

    public function getImageAttribute($image)
    {
        $images = json_decode($image, true) ?? [];
// return 'a';
        // return $images;
        return array_map(fn($img) => asset('uploads/about_images/' . $img), $images);
    }


}
