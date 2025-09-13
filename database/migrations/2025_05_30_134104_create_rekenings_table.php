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
      Schema::create('rekenings', function (Blueprint $table) {
            $table->id(); // auto-incrementing primary key
            $table->string('kode_rekening')->unique(); // unique identifier for the account
            $table->string('nama_rekening'); // name of the account
            $table->enum('jenis_akun', [
                'default',
                'uang_tunai',
                'kartu_kredit',
                'rekening_virtual',
                'investasi',
                'piutang',
                'hutang'
            ]); // account type (enum for predefined options)
            $table->decimal('jumlah', 15, 2)->default(0); // amount (decimal with 2 decimals)
            $table->text('keterangan')->nullable(); // optional description
            $table->string('auth'); // optional description
            $table->timestamps(); // created_at and updated_at timestamps
        });  
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekenings');
    }
};
