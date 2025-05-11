<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chat extends Model
{

     use HasFactory;


    protected $casts = [
        'buyer_message' => 'array',
        'seller_message' => 'array',
    ];


    protected $fillable = [
        'buyer_email', 'seller_email', 'buyer_message', 'seller_message', 'trx_id', 'message'
    ];  
}
