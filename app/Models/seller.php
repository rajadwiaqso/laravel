<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class seller extends Model
{
    protected $fillable = [
        'name', 'email', 'profile_picture' ,'credits', 'sold_total', 'product_total', 'diproses'
    ];
}
