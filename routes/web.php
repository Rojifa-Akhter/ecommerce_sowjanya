<?php

use App\Models\Order;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/details',function(){
    
$orders=Order::get();

    $data=[
        'image'=>'apnar inage',
        'product_name'=>'title',
        'price'=>1,
        'status'=>'pending',
        'review'=>'4'
    ];
  return $data;
});