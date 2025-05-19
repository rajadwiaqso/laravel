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
        Schema::create('seller_forms', function (Blueprint $table) {
    $table->id();
    $table->string('fullname');
    $table->string('name'); // nama toko
    $table->string('phone');
    $table->boolean('ktp'); // 1 = punya, 0 = tidak
    $table->string('nik')->nullable(); // nullable jika tidak punya KTP
    $table->string('img')->nullable(); // path file KTP, nullable jika tidak punya KTP
    $table->text('message')->nullable(); // pesan tambahan
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::dropIfExists('seller_forms');
    }
};
