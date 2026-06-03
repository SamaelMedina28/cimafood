<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 3 Vendedores
        User::create([
            'name' => 'Carlos Rodríguez',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'is_vendor' => true,
            'phone' => '5512345678',
        ]);

        User::create([
            'name' => 'María González',
            'email' => 'maria@hamburguesas.com',
            'password' => bcrypt('password'),
            'is_vendor' => true,
            'phone' => '5523456789',
        ]);

        User::create([
            'name' => 'Roberto Martínez',
            'email' => 'roberto@sushi.com',
            'password' => bcrypt('password'),
            'is_vendor' => true,
            'phone' => '5534567890',
        ]);

        // 1 Cliente
        User::create([
            'name' => 'Ana López',
            'email' => 'ana@cliente.com',
            'password' => bcrypt('password'),
            'is_vendor' => false,
            'phone' => '5545678901',
        ]);
    }
}
