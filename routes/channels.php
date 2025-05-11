<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;


Broadcast::channel('product.{productId}', function ($user, $productId) {
    return true; // Atau logika otorisasi pengguna jika diperlukan
});

Broadcast::channel('chat.{trxId}', function ($user, $trxId) {
    $chat = Chat::where('trx_id', $trxId)
        ->where(function ($query) use ($user) {
            $query->where('buyer_email', $user->email)
                  ->orWhere('seller_email', $user->email);
        })
        ->exists();

    

    return $chat; // Hanya mengizinkan pengguna yang terlibat dalam percakapan
});
