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
        Schema::create('block_ledgers', function (Blueprint $table) {
            $table->id();
            $table->text('data'); // Data JSON dari transaksi
            $table->timestamp('timestamp'); // Waktu pembuatan blok
            
            // Perbaikan agar Blok Genesis bisa tercetak
            $table->string('previous_hash', 64)->nullable(); 
            
            $table->string('current_hash', 64)->unique(); // Hash dari blok saat ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('block_ledgers');
    }
};
