<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerForm extends Model
{
   protected $fillable = [
        'fullname',   // Nama lengkap
        'name',       // Nama toko
        'phone',      // Nomor telepon
        'ktp',        // Status punya KTP (1/0)
        'nik',        // NIK (nullable)
        'img',        // Path file KTP (nullable)
        'message',    // Pesan tambahan (nullable)
        'from',       // Email pengirim
    ];
  
}
