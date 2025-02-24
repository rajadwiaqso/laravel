<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Mongo;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\BuyerMiddleware;
use App\Http\Middleware\SellerMiddleware;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Route;



Route::middleware('auth')->group(function (){

    Route::get('/mongo', [Mongo::class, 'index']);

    Route::post('/logout', [BuyerController::class, 'logout'])->name('logout');
    Route::get('/', [BuyerController::class, 'index'])->name('index')->middleware(BuyerMiddleware::class);
    Route::get('/profile', [BuyerController::class, 'profile'])->name('profile');
    Route::post('/profile/post/{id}', [BuyerController::class, 'updateProfile'])->name('profile.post');

    Route::get('/seller/form', [SellerController::class, 'form'])->name('seller.form');
    Route::post('/seller/post', [SellerController::class, 'post'])->name('seller.post');

    Route::get('/orders', [BuyerController::class, 'orders'])->name('buyer.orders');

    Route::get('/chat/{buyer}/{seller}/{trx}', [ChatController::class , 'buyerChat'])->name('buyer.chat');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('buyer.chat.send');
    
    Route::post('/confirm/{id}', [BuyerController::class, 'confirm'])->name('buyer.confirm');

    Route::middleware(AdminMiddleware::class)->group(function (){
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.index');
   
        Route::post('/admin/accept/{id}', [AdminController::class, 'accept'])->name('form.accept');
        Route::post('/admin/decline/{id}', [AdminController::class, 'decline'])->name('form.decline');
    });
    Route::middleware(SellerMiddleware::class)->group(function (){
        Route::get('/seller/dashboard', [SellerController::class, 'index'])->name('seller.index');
    });

    Route::get('/products/{category}', [ProductController::class, 'index'])->name('products.category');
    Route::get('/products/{category}/{store}/{product}', [ProductController::class, 'details'])->name('product.details');

    Route::post('/products/{category}/{store}/{product}/buy', [ProductController::class, 'buy'])->name('product.buy');
  
    Route::get('/seller/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/seller/create/submit', [ProductController::class , 'createSubmit'])->name('product.create.submit');

    Route::get('/seller/product', [ProductController::class ,'list'])->name('product.list');

    Route::post('/seller/product/delete/{id}', [ProductController::class, 'delete'])->name('product.delete');
    Route::post('/seller/edit/{id}/submit', [ProductController::class , 'editSubmit'])->name('product.edit.submit');
    Route::get('/seller/edit/{id}', [ProductController::class , 'edit'])->name('product.edit');

    Route::get('/seller/orders', [SellerController::class, 'ordersView'])->name('seller.orders');
    Route::post('seller/orders/accept/{id}', [SellerController::class, 'ordersAccept'])->name('seller.orders.accept');
    Route::post('seller/orders/reject/{id}', [SellerController::class, 'ordersReject'])->name('seller.orders.reject');

    Route::get('/seller/chat/{buyer}/{seller}/{trx}', [ChatController::class , 'sellerChat'])->name('seller.chat');
    Route::post('/seller/chat/send', [ChatController::class, 'sendMessage'])->name('seller.chat.send');

    Route::get('/seller/confirmed', [SellerController::class , 'confirmedView'])->name('seller.confirmed');
    Route::get('/seller/done', [SellerController::class , 'doneView'])->name('seller.done');
    
});

Route::middleware('guest')->group(function (){
    Route::get('/signup', [BuyerController::class, 'signUpView'])->name('signup.view');
    Route::post('/signup/post', [BuyerController::class, 'signUpPost'])->name('signup.post');
    Route::get('/signin', [BuyerController::class, 'signInView'])->name('signin.view');
    Route::post('/signin/post', [BuyerController::class, 'signInPost'])->name('signin.post');
    
});

