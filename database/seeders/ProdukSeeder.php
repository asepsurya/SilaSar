<?php

namespace Database\Seeders;

use App\Models\Satuan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Pastikan ada beberapa satuan dulu
        $pcs = Satuan::firstOrCreate(['nama' => 'pcs']);
        $box = Satuan::firstOrCreate(['nama' => 'box']);
        $kg  = Satuan::firstOrCreate(['nama' => 'kg']);
    }
}
