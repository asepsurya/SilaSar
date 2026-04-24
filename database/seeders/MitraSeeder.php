<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mitra;

class MitraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            'Bandung' => ['lat' => -6.9175, 'lng' => 107.6191],
            'Jakarta' => ['lat' => -6.2088, 'lng' => 106.8456],
            'Surabaya' => ['lat' => -7.2575, 'lng' => 112.7521],
        ];

        $index = 1;
        foreach ($cities as $cityName => $coords) {
            for ($i = 1; $i <= 5; $i++) {
                // Slightly randomize location within ~1-2km
                $lat = $coords['lat'] + (rand(-100, 100) / 5000);
                $lng = $coords['lng'] + (rand(-100, 100) / 5000);

                Mitra::create([
                    'kode_mitra' => 'MIT' . str_pad($index, 4, '0', STR_PAD_LEFT),
                    'nama_mitra' => "Toko {$cityName} Sample {$i}",
                    'alamat_mitra' => "Jl. Sample No. {$i}, {$cityName}",
                    'no_telp_mitra' => '0812' . rand(10000000, 99999999),
                    'id_kota' => $cityName,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'foto' => 'https://images.unsplash.com/photo-1449156001931-9a99bc9de27e?auto=format&fit=crop&q=80&w=400',
                    'auth' => 'sample',
                ]);
                $index++;
            }
        }
    }
}
