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
        Schema::create('itemdokumens', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi');
            $table->string('nama_barang');
            $table->integer('qty');
            $table->string('unit');
            $table->decimal('harga', 15, 2);
            $table->decimal('total', 15, 2);
            $table->string('auth');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itemdokumens');
    }
};
