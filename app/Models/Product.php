<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'image' => 'array',
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
