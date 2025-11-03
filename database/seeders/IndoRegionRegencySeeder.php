<?php

/*
 * This file is part of the IndoRegion package.
 *
 * (c) Azis Hapidin <azishapidin.com | azishapidin@gmail.com>
 *
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use AzisHapidin\IndoRegion\RawDataGetter;
use Illuminate\Support\Facades\DB;

class IndoRegionRegencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @deprecated
     * 
     * @return void
     */
    public function run()
    {
        // Get Data
        $regencies = RawDataGetter::getRegencies();

        // Insert Data to Database
       foreach ($regencies as $regency) {
            DB::table('regencies')->updateOrInsert(
                ['id' => $regency['id']], // kolom unik, biasanya ID atau kode
                [
                    'province_id' => $regency['province_id'],
                    'name' => $regency['name'],
                ]
            );
        }
    }
}
