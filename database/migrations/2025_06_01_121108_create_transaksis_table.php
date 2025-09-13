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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_transaksi')->nullable();
            $table->string('kode_transaksi')->unique();
            $table->string('kode_mitra')->nullable();
            $table->string('diskon')->nullable();
            $table->date('tanggal_pembayaran')->nullable();
            $table->string('total')->nullable();
            $table->string('status_bayar')->nullable();
            $table->string('auth');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
