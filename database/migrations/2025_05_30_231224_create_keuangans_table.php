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
        Schema::create('keuangans', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal');
            $table->string('deskripsi');
            $table->unsignedBigInteger('id_akun');
            $table->enum('tipe', ['pemasukan', 'pengeluaran']);
            $table->decimal('total', 15, 2);
            $table->string('id_rekening');
            $table->string('foto')->nullable();
            $table->unsignedBigInteger('auth');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangans');
    }
};
