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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        
        // Foreign Keys (Kolom ID)
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('product_id');

        // Data Transaksi
        $table->unsignedInteger('quantity');
        $table->unsignedBigInteger('total_amount');
        $table->string('status')->default('in progress');

        // Data Pengiriman/Penerima
        $table->string('name')->nullable();
        $table->string('rec_address')->nullable();
        $table->string('phone')->nullable();
        
        // Timestamps (HANYA SEKALI)
        $table->timestamps();

        // Foreign Key Constraints
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
