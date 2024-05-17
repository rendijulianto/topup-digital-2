<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = \App\Models\Pengguna::create([
            'nama' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'jabatan' => 'admin',
        ])->pin()->create([
            'pin' => bcrypt('123456')
        ]);
        $cashier = \App\Models\Pengguna::create([
            'nama' => 'Kasir 1',
            'email' => 'kasir@gmail.com',
            'password' => bcrypt('123456'),
            'jabatan' => 'kasir',
        ])->pin()->create([
            'pin' => bcrypt('123456')
        ]);

        $injector = \App\Models\Pengguna::create([
            'nama' => 'Injector 1',
            'email' => 'injector@gmail.com',
            'password' => bcrypt('123456'),
            'jabatan' => 'injector',
        ])->pin()->create([
            'pin' => bcrypt('123456')
        ]);
    }
}
