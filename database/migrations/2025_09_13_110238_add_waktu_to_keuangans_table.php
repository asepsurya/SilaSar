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
        Schema::table('keuangan_tables', function (Blueprint $table) {
            $table->string('waktu')->nullable();
            $table->unsignedBigInteger('id_akun_second')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keuangan_tables', function (Blueprint $table) {
            $table->string('waktu')->nullable();
            $table->unsignedBigInteger('id_akun_second')->nullable();
        });
    }
};
