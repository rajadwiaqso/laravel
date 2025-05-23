<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trxes', function (Blueprint $table) {
            $table->id();
            $table->string('buyer_email');
            $table->string('seller_email');
            $table->string('product');
            $table->string('price');
            $table->string('category');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trxes');
    }
};
