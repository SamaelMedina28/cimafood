<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $client = User::where('email', 'ana@cliente.com')->first();

        $favorites = Product::whereIn('name', ['Burrito de Carne Asada', 'Tacos de Pastor', 'California Roll'])->pluck('id');

        $client->favorites()->syncWithoutDetaching($favorites);
    }
}
