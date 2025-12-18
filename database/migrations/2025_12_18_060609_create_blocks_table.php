<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('blocks', function (Blueprint $table) {
        $table->id();
        $table->integer('index'); // Urutan blok (Genesis = 0, dst)
        $table->timestamp('timestamp'); // Waktu pencatatan
        $table->longText('data'); // Data transaksi (disimpan sbg JSON)
        $table->string('previous_hash'); // Hash blok sebelumnya (KUNCI INTEGRITAS)
        $table->string('hash'); // Hash blok ini
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
