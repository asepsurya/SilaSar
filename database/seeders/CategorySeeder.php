<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Makanan Ringan',
            'Makanan Berat',
            'Camilan Sehat',
            'Keripik',
            'Permen & Coklat',
            'Kue Kering',
            'Minuman',
            'Elektronik',
            'Pakaian',
            'Peralatan Rumah Tangga',
            'Kecantikan',
            'Olahraga',
            'Mainan Anak',
        ];
        
        foreach ($categories as $category) {
            \App\Models\CategoryProduct::updateOrCreate(
                ['name' => $category],
                ['name' => $category]
            );
        }
        

    }
}
