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
        Schema::create('ikms', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->nullable();
            $table->string('nama')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin')->nullable();
            // alamat
            $table->string('alamat')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('id_provinsi')->nullable();
            $table->string('id_kota')->nullable();
            $table->string('id_kecamatan')->nullable();
            $table->string('id_desa')->nullable();

            $table->string('agama')->nullable();
            $table->string('status_perkawinan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('kewarganegaraan')->nullable();
            $table->string('telp')->nullable();
            $table->string('sosmed')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('foto')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ikms');
    }
};
