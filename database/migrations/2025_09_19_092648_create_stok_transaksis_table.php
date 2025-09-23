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
         Schema::create('stok_transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->unique();
            $table->date('tanggal');
            $table->text('deskripsi')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('potongan', 15, 2)->default(0);
            $table->decimal('pajak', 5, 2)->default(0);
            $table->decimal('total_akhir', 15, 2)->default(0);
            $table->string('auth');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_transaksis');
    }
};
