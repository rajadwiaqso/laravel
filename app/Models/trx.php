<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class trx extends Model
{
    protected $fillable = [
        'buyer_email', 'seller_email', 'category', 'product', 'price', 'status', 'trx_id', 'rating', 'status_date', 'quantity', 'total'
    ];

    protected $casts = [
        'rating' => 'array',
        'status_date' => 'array'
    ];
}
