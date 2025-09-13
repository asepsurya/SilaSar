<?php

namespace Database\Seeders;

use App\Models\ikm;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // ----------------------------------------------------
    // --------------- CREATE ROLE ------------------------
    // -----------------------------------------------------
    Role::updateOrCreate(['name' => 'superadmin']);
    Role::updateOrCreate(['name' => 'admin']);
    // tingkatan pengguna
    Role::updateOrCreate(['name' => 'platinum']);
    Role::updateOrCreate(['name' => 'gold']);

    // ----------------------------------------------------
    // -------- ROLE SUPER gold --------------------------
    // ----------------------------------------------------- 
    
    $user1 = User::updateOrCreate(
        ['email' => 'asepsurya1998@gmail.com'],
        [
            'name'              => 'Master Administration',
            'phone'             => '087731402487',
            'password'          => Hash::make('newinopak'),
            'role'              => 'superadmin',
            'email_verified_at' => now(),
        ]
    );
     ikm::updateOrCreate(
        ["email" => "asepsurya1998@gmail.com"],
        [
            "nama" => "Master Administration",
            "telp" => "087731402487",
        ]
      );
    $user1->assignRole('superadmin'); 

    // ----------------------------------------------------
    // --------ROLE gold ----------------------------------
    // -----------------------------------------------------

     $user2 = User::updateOrCreate(
        ['email' => 'admin@silasar.com'],
        [
            'name'              => 'Administration',
            'phone'             => '0812554588997',
            'password'          => Hash::make('newinopak'),
            'role'              => 'admin',
            'email_verified_at' => now(),
        ]
    );
     ikm::updateOrCreate(
        ["email" => "admin@silasar.com"],
        [
            "nama" => "Administration",
            "telp" => "0812554588997",
        ]
      );
      $user2->assignRole('admin'); 

    // ----------------------------------------------------
    // -------- ROLE silver ----------------------------
    // -----------------------------------------------------

     $user3 = User::updateOrCreate(
        ['email' => 'pengguna@silasar.com'],
        [
            'name'              => 'Pengguna Silasar',
            'phone'             => '08225446558',
            'password'          => Hash::make('newinopak'),
            'role'              => 'gold',
            'email_verified_at' => now(),
        ]
    );
     ikm::updateOrCreate(
        ["email" => "pengguna@silasar.com"],
        [
            "nama" => "Pengguna Silasar",
            "telp" => "08225446558",
        ]
      );
      $user3->assignRole('gold'); 
    
    // ----------------------------------------------------
    // -------- kelompok pengguna ----------------------------
    // -----------------------------------------------------
    
      $users = [
    // Kelompok 1
    ['name' => 'Retno Damayanti', 'phone' => '081283442898'],
    ['name' => 'Supriyanti', 'phone' => '082223668235'],
    ['name' => 'Padmi', 'phone' => '085214953409'],
    ['name' => 'Siti Yuliana', 'phone' => '0895424023173'],
    ['name' => 'Sri Windarti', 'phone' => '085234631183'],
    ['name' => 'Suat', 'phone' => '081227875949'],

    // Kelompok 2
    ['name' => 'Winda Rini A', 'phone' => '085702116045'],
    ['name' => 'Panisih', 'phone' => '082220809662'],
    ['name' => 'Gumiyati', 'phone' => '085286655676'],
    ['name' => 'Romlah', 'phone' => '081'],
    ['name' => 'Endah Rahayu', 'phone' => '0895339816698'],
    ['name' => 'Sumarni', 'phone' => '0895339816699'],
    ['name' => 'Sumiati', 'phone' => '082'],

    // Kelompok 3
    ['name' => 'Siti Rosidah', 'phone' => '085890666276'],
    ['name' => 'Shofiyanti', 'phone' => '088226445855'],
    ['name' => 'Sutini', 'phone' => '083'],
    ['name' => 'Supikatun', 'phone' => '082313962103'],
    ['name' => 'Rasni', 'phone' => '0895367158846'],
    ['name' => 'Khilatul Laila', 'phone' => '089521753264'],

    // Kelompok 4
    ['name' => 'Nur Rohmah', 'phone' => '089668961099'],
    ['name' => 'Supini', 'phone' => '084'],
    ['name' => 'Siti Romlah', 'phone' => '085165413161'],
    ['name' => 'Ngaeni', 'phone' => '085'],
    ['name' => "Robi'ah Adawiyah", 'phone' => '082134483157'],
    ['name' => 'Sri Hartatik', 'phone' => '085137460616'],

    // Kelompok 5
    ['name' => "Jumi'ati", 'phone' => '085779498190'],
    ['name' => 'Hj. Endang Mindarsih', 'phone' => '081312667096'],
    ['name' => 'Ratmi', 'phone' => '086'],
    ['name' => 'Supikati', 'phone' => '082313929067'],
    ['name' => 'Sri Pancastuti', 'phone' => '0895369833666'],
    ['name' => 'Riyanti', 'phone' => '089514169809'],
];

// pastikan role "gold" ada
Role::firstOrCreate(['name' => 'gold']);

foreach ($users as $u) {
    // bikin email dari nama (lowercase + hapus spasi/tanda kutip)
    $email = strtolower(
        preg_replace('/[^a-z0-9]/', '', str_replace(' ', '', $u['name']))
    ) . '@silasar.com';

    $user = User::updateOrCreate(
        ['email' => $email],
        [
            'name'              => $u['name'],
            'phone'             => $u['phone'],
            'password'          => Hash::make('srikandilaut'),
            'email_verified_at' => now(),
            'role'              => 'gold',
            
        ]
    );

    ikm::updateOrCreate(
        ['email' => $email],
        [
            'nama' => $u['name'],
            'telp' => $u['phone'],
        ]
    );

    // assign role gold
    if (!$user->hasRole('gold')) {
        $user->assignRole('gold');
    }
}
  }
}
