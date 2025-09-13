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
        Schema::create('dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kode_transaksi');
            $table->string('tanggal');
            $table->string('alamat_company');
            $table->string('telp_company');
            $table->string('email_company');
            $table->string('kota');
            $table->string('telp');
            $table->string('kepada');
            $table->string('keterangan');
            $table->string('auth');
            $table->string('grandtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumens');
    }
};
