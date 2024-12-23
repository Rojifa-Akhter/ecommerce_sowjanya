<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsToMany(User::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
