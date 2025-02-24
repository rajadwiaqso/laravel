<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    //
    protected $fillable = [
        'name', 'email', 'credits', 'sold_total', 'product_total'
    ];
    protected $primaryKey = 'email';
}
