<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerForm extends Model
{
    protected $fillable = [
        'name', 'email', 'img', 'message'
    ];
  
}
