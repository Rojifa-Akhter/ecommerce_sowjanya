<?php

use App\Http\Controllers\backend\AboutController;
use App\Http\Controllers\backend\AuthController;
use App\Http\Controllers\backend\BlogController;
use App\Http\Controllers\backend\FAQController;
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
   Route::get('/category-list', [ProductController::class, 'categoryList']);

   // Product Routes
   Route::post('/product-add', [ProductController::class, 'productAdd']);
   Route::post('/product-update/{id}', [ProductController::class, 'productUpdate']);
   Route::delete('/product-delete/{id}', [ProductController::class, 'productDelete']);
   Route::get('/product-list', [ProductController::class, 'productList']);

   // blog us Routes
   Route::post('/blog-add', [BlogController::class, 'blogAdd']);
   Route::post('/blog-update/{id}', [BlogController::class, 'blogUpdate']);
   Route::delete('/blog-delete/{id}', [BlogController::class, 'blogDelete']);

   // about us Routes
   Route::post('/about-add', [AboutController::class, 'aboutAdd']);

    // FAQ us Routes
    Route::post('/faq-add', [FAQController::class, 'faqAdd']);

    //users
    Route::get('/users', [AuthController::class, 'viewUserInfo']);
    Route::delete('/users/{id}', [AuthController::class, 'deleteUser']);

    //dashboard
    Route::get('/statistics', [AuthController::class, 'getDashboardStatistics']);
    Route::get('/analytics', [AuthController::class, 'analytics']);
    Route::get('/most-earning', [AuthController::class, 'earningChart']);

    //get notification after order
    Route::get('/notify',[OrderController::class, 'getAdminNotifications']);
    Route::get('/notify/{id}', [OrderController::class, 'markNotification']);


});
Route::get('/product-view', [UserController::class, 'productView']);
Route::middleware(['auth:api', 'USER'])->group(function () {
    Route::get('/blog-list', [BlogController::class, 'blogList']);

    Route::get('/blog-details/{id}', [BlogController::class, 'blogDetails']);
    Route::get('/aboutus', [UserController::class, 'aboutUs']);

    Route::get('/my-order-list', [UserController::class, 'myOrder']);
    Route::get('/own-profile', [UserController::class, 'ownProfile']);
    Route::get('/notifications', [UserController::class, 'getNotifications']);
    Route::get('/notifications/{id}', [UserController::class, 'markNotificationAsRead']);
    Route::get('/showProduct/{id}', [OrderController::class, 'showProduct']);
    Route::get('/review-by-product', [UserController::class, 'reviewList']);

    Route::post('/create-order', [OrderController::class, 'payment']);
    Route::post('/success-payment', [OrderController::class, 'payment']);

    // Review
    Route::post('/reviews', [UserController::class, 'createReview']);

 });


