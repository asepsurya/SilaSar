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
       Schema::table('produks', function (Blueprint $table) {
            $table->unsignedBigInteger('harga_jual')->nullable()->after('harga');
            $table->unsignedBigInteger('hpp_id')->nullable()->after('satuan');
            $table->unsignedBigInteger('pendapatan_id')->nullable()->after('hpp_id');
            $table->unsignedBigInteger('pendapatan_lainnya_id')->nullable()->after('pendapatan_id');
            $table->unsignedBigInteger('persediaan_id')->nullable()->after('pendapatan_lainnya_id');
            $table->unsignedBigInteger('beban_non_inventory_id')->nullable()->after('persediaan_id');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn([
                'harga_jual',
                'hpp_id',
                'pendapatan_id',
                'pendapatan_lainnya_id',
                'persediaan_id',
                'beban_non_inventory_id'
            ]);
        });
        });
    }
};
