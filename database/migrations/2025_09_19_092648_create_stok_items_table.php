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
          Schema::create('stok_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_transaksi_id');
            $table->string('kode_produk');
            $table->string('nama_produk');
            $table->integer('jumlah')->default(0);
            $table->string('satuan')->nullable();
            $table->decimal('harga', 15, 2)->default(0);
            $table->decimal('pot', 5, 2)->default(0); // persentase
            $table->decimal('total', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_items');
    }
};
