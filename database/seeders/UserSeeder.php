<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'role' => 'admin',
        ]);
        
        $cashier = \App\Models\User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@gmail.com',
            'password' => bcrypt('123456'),
            'role' => 'kasir',
        ])->pin()->create([
            'pin' => bcrypt('123456')
        ]);

        $injector = \App\Models\User::create([
            'name' => 'Injector 1',
            'email' => 'injector@gmail.com',
            'password' => bcrypt('123456'),
            'role' => 'injector',
        ])->pin()->create([
            'pin' => bcrypt('123456')
        ]);
    }
}
