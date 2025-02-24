<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MongoDB\Driver\Manager;

class Mong extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'users'; 
    protected $fillable = ['nomor', 'saldo', 'role']; 
}
