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
        Schema::create('transaksi_products', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->nullable();
            $table->string('kode_produk')->nullable();
            $table->string('kode_mitra')->nullable();
            $table->integer('barang_keluar')->nullable();
            $table->integer('barang_terjual')->nullable();
            $table->integer('barang_retur')->nullable();
            $table->string('total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_products');
    }
};
