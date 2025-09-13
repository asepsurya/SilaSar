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
        Schema::table('perusahaans', function (Blueprint $table) {
             $table->text('id_provinsi')->nullable();
             $table->text('id_kota')->nullable();
             $table->text('id_kecamatan')->nullable();
             $table->text('id_desa')->nullable();
             $table->text('stamp')->nullable();
             $table->text('ttd')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perusahaans', function (Blueprint $table) {
             $table->dropColumn(['id_provinsi','id_kota','id_kecamatan','id_desa','stamp','ttd']);
        });
    }
};
