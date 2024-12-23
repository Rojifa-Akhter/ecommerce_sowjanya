<?php

use App\Http\Controllers\backend\AuthController;
use App\Http\Controllers\backend\OrderController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('verify', [AuthController::class, 'verify']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('/profile-update', [AuthController::class, 'updateProfile'])->middleware('auth:api');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('auth:api');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/verifyOtp', [AuthController::class, 'verifyOtp']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);

});

Route::middleware(['auth:api', 'ADMIN'])->group(function () {
   // Category Routes
   Route::post('/category-add', [ProductController::class, 'categoryAdd']);
   Route::post('/category-update/{id}', [ProductController::class, 'categoryUpdate']);
   Route::delete('/category-delete/{id}', [ProductController::class, 'categoryDelete']);

   // Product Routes
   Route::post('/product-add', [ProductController::class, 'productAdd']);
   Route::post('/product-update/{id}', [ProductController::class, 'productUpdate']);
   Route::delete('/product-delete/{id}', [ProductController::class, 'productDelete']);

   // blog us Routes
   Route::post('/blog-add', [ProductController::class, 'blogAdd']);
   Route::post('/blog-update/{id}', [ProductController::class, 'blogUpdate']);
   Route::delete('/blog-delete/{id}', [ProductController::class, 'blogDelete']);

   // about us Routes
   Route::post('/about-add', [ProductController::class, 'aboutAdd']);
   Route::post('/about-update/{id}', [ProductController::class, 'aboutUpdate']);
   Route::delete('/about-delete/{id}', [ProductController::class, 'aboutDelete']);

    // FAQ us Routes
    Route::post('/faq-add', [ProductController::class, 'faqAdd']);
    Route::post('/faq-update/{id}', [ProductController::class, 'faqUpdate']);
    Route::delete('/faq-delete/{id}', [ProductController::class, 'faqDelete']);

    //users
    Route::get('/users', [AuthController::class, 'viewUserInfo']);

    //dashboard
    Route::get('/total-user', [AuthController::class, 'getDashboardStatistics']);
    Route::get('/website-visitor', [AuthController::class, 'websiteVisitor']);
    Route::get('/traffic-sourch', [AuthController::class, 'trafficSourch']);


});
Route::middleware(['auth:api', 'USER'])->group(function () {
    Route::get('/notifications', [UserController::class, 'getNotifications']);
    Route::get('/notifications/{id}', [UserController::class, 'markNotificationAsRead']);
    Route::get('/showProduct/{id}', [OrderController::class, 'showProduct']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/create-order', [OrderController::class, 'createOrder']);

 });

Route::middleware(['auth:api'])->group(function () {
    // Routes accessible to both ADMIN and USER
    Route::get('/category-list', [ProductController::class, 'categoryList']);
    Route::get('/product-list', [ProductController::class, 'productList']);
    Route::get('/blog-list', [ProductController::class, 'blogList']);
});
