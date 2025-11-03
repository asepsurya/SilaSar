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

  }
}
