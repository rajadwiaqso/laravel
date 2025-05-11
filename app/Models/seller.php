<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class seller extends Model
{
    protected $fillable = [
        'name', 'email', 'credits', 'sold_total', 'product_total', 'diproses'
    ];
}
