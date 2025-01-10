<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{

    use HasFactory;
    protected $guarded = ['id'];

    // protected $casts = [
    //     'image' => 'array',
    // ];
    public function getImageAttribute($image)
    {
        $defaultImage = 'default_user.png';
        return asset('uploads/blog_images/' . ($image ?: $defaultImage));


    }

}
