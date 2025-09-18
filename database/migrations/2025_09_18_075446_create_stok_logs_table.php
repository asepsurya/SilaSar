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
        Schema::create('stok_logs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk');
            $table->enum('tipe', ['keluar', 'masuk']);
            $table->integer('jumlah');
            $table->string('sumber')->nullable(); // contoh: transaksi, retur, penyesuaian
            $table->string('referensi')->nullable(); // contoh: kode_transaksi
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('auth'); // user yang melakukan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_logs');
    }
};
