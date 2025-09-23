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
              $table->unsignedBigInteger('satuan_id')->nullable()->after('stok');

                // kalau mau pakai foreign key
                $table->foreign('satuan_id')->references('id')->on('satuans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
             $table->dropForeign(['satuan_id']);
             $table->dropColumn('satuan_id');
        });
    }
};
