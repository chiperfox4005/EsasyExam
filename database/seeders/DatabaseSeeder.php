<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Seeder Data Awal Sistem
        |--------------------------------------------------------------------------
        */

        $this->call([
            InitialDataSeeder::class,
        ]);


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        User::firstOrCreate(
            [
                'email' => 'admin@esasyexam.com'
            ],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | GURU
        |--------------------------------------------------------------------------
        */

        User::firstOrCreate(
            [
                'email' => 'guru@esasyexam.com'
            ],
            [
                'name' => 'Guru Contoh',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'nip' => '123456789',
                'phone' => '081234567891',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | SISWA (LOGIN NISN)
        |--------------------------------------------------------------------------
        */

        User::firstOrCreate(
            [
                'nisn' => '0012345678'
            ],
            [
                'name' => 'Siswa Contoh',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'phone' => '081234567892',
            ]
        );
    }
}
