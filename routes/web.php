<?php

use App\Events\NewChatMessage;
use App\Events\ProductStockUpdated;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Mongo;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\BuyerMiddleware;
use App\Http\Middleware\SellerMiddleware;
use App\Http\Middleware\Verify;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SearchController; // Pastikan controller API ini ada
use App\Http\Middleware\PageMiddleware;
use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth']]);



Route::middleware(PageMiddleware::class)->group(function () {
    

Route::middleware(Verify::class)->group(function () {
   

Route::get('/', [BuyerController::class, 'index'])->name('index');
Route::get('/saldo', [BuyerController::class, 'checkSaldo'])->name('saldo');

Route::get('/products/category/{category:slug}', [ProductController::class, 'categoryProducts'])->name('products.category');
Route::get('/products/search', [ProductController::class, 'searchProducts'])->name('products.search');


Route::get('/products/{category}', [ProductController::class, 'index'])->name('products.category');
Route::get('/products/{category}/{store}/{product}/{id_product}', [ProductController::class, 'details'])->name('product.details');
Route::post('/products/{category}/{store}/{product}/{id_product}/buy', [ProductController::class, 'buy'])->name('product.buy');
  
});

Route::get('/password/reset', [BuyerController::class, 'resetPasswordView'])->name('password.reset.view');
Route::post('/password/reset/send-otp', [BuyerController::class, 'sendResetOtp'])->name('password.reset.sendOtp');
Route::post('/password/reset', [BuyerController::class, 'resetPassword'])->name('password.reset');


});




Route::middleware('auth')->group(function (){

    
    Route::get('/verify', [BuyerController::class, 'verify'])->name('verify.view');
    Route::post('/verify-email', [BuyerController::class, 'verifyEmail'])->name('verify.email');
    Route::middleware(Verify::class)->group(function () {
 


/// buyer ///

    Route::middleware(BuyerMiddleware::class)->group(function (){
      

        Route::get('/test', function (){
            broadcast(new ProductStockUpdated('51','12'))->toOthers();
            return 'hello';
        });
        
        Route::get('/seller/form', [SellerController::class, 'form'])->name('seller.form');
        Route::post('/seller/post', [SellerController::class, 'post'])->name('seller.post');
        Route::get('/orders', [BuyerController::class, 'orders'])->name('buyer.orders');
        Route::post('/orders/reject/{id}', [BuyerController::class, 'ordersReject'])->name('orders.reject');

        Route::get('/chat/{trx}', [ChatController::class , 'buyerChat'])->name('buyer.chat');
        Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('buyer.chat.send');

        
        
        Route::post('/confirm/{id}', [BuyerController::class, 'confirm'])->name('buyer.confirm');
    
        Route::get('/rating/{trx}', [ProductController::class , 'ratingView'])->name('buyer.rating');
        Route::post('/rating/{trx}/send', [ProductController::class, 'ratingPost'])->name('buyer.rating.post');
    
    });
    
    /////

    Route::post('/logout', [BuyerController::class, 'logout'])->name('logout');
   


    Route::get('/profile', [BuyerController::class, 'profile'])->name('profile');
    Route::post('/profile/post/{id}', [BuyerController::class, 'updateProfile'])->name('profile.post');
    Route::post('/profile/change-password/{id}', [BuyerController::class, 'changePassword'])->name('password.update');




  
    /// admin ///

    Route::middleware(AdminMiddleware::class)->group(function (){
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.index');
   
        Route::post('/admin/accept/{id}', [AdminController::class, 'accept'])->name('form.accept');
        Route::post('/admin/decline/{id}', [AdminController::class, 'decline'])->name('form.decline');
    });

    /////

    /// seller ///

    Route::middleware(SellerMiddleware::class)->group(function (){
        Route::get('/seller/dashboard', [SellerController::class, 'index'])->name('seller.index');

    Route::get('/seller/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/seller/create/submit', [ProductController::class , 'createSubmit'])->name('product.create.submit');

    Route::get('/seller/product', [ProductController::class ,'list'])->name('product.list');

    Route::post('/seller/product/delete/{id}', [ProductController::class, 'delete'])->name('product.delete');
    Route::post('/seller/edit/{id}/submit', [ProductController::class , 'editSubmit'])->name('product.edit.submit');
    Route::get('/seller/edit/{id}', [ProductController::class , 'edit'])->name('product.edit');

    Route::get('/seller/orders', [SellerController::class, 'ordersView'])->name('seller.orders');
    Route::post('seller/orders/accept/{id}', [SellerController::class, 'ordersAccept'])->name('seller.orders.accept');
    Route::post('seller/orders/reject/{id}', [SellerController::class, 'ordersReject'])->name('seller.orders.reject');

    Route::get('/seller/chat/{trx}', [ChatController::class , 'sellerChat'])->name('seller.chat');
    Route::post('/seller/chat/send', [ChatController::class, 'sendMessage'])->name('seller.chat.send');

    Route::get('/seller/confirmed', [SellerController::class , 'confirmedView'])->name('seller.confirmed');
    Route::get('/seller/done', [SellerController::class , 'doneView'])->name('seller.done');
    Route::get('/seller/reject', [SellerController::class , 'rejectView'])->name('seller.reject');
    Route::get('/seller/orders/all', [SellerController::class , 'ordersAllView'])->name('seller.orders.all');

    Route::post('/seller/reject/terima/{id}', [SellerController::class, 'terimaSaran'])->name('terima.saran');
    Route::post('/seller/reject/tolak/{id}', [SellerController::class, 'tolakSaran'])->name('tolak.saran');

    Route::get('/seller/products/search', [ProductController::class, 'searchProducts'])->name('seller.products.search');
    });

    //////

        
   
});
});

Route::middleware('guest')->group(function (){

 

    Route::get('/signup', [BuyerController::class, 'signUpView'])->name('signup.view');
    Route::post('/signup/post', [BuyerController::class, 'signUpPost'])->name('signup.post');
    Route::get('/signin', [BuyerController::class, 'signInView'])->name('signin.view');
    Route::post('/signin/post', [BuyerController::class, 'signInPost'])->name('signin.post');

    
  
    
});

