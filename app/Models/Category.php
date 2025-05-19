<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        // Kolom lain
    ];
    

    // Jika Anda ingin slug otomatis dibuat saat menyimpan
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    public function products()
    {
        
        return $this->hasMany(Product::class, 'category'); // Sesuaikan 'category' dengan nama kolom foreign key Anda
    }
}