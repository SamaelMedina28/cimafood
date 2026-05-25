<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vendor 1 - Burritos El Mexicano
        $business1 = \App\Models\Business::where('name', 'Burritos El Mexicano')->first();

        \App\Models\Product::create([
            'name' => 'Burrito de Carne Asada',
            'description' => 'Burrito con carne asada, arroz, frijoles, guacamole y salsa verde.',
            'price' => 85.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 50,
            'status' => 'available',
            'business_id' => $business1->id,
        ]);

        \App\Models\Product::create([
            'name' => 'Burrito de Pollo',
            'description' => 'Burrito con pollo desmenuzado, arroz, frijoles, pico de gallo y crema.',
            'price' => 75.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 45,
            'status' => 'available',
            'business_id' => $business1->id,
        ]);

        \App\Models\Product::create([
            'name' => 'Burrito Supremo',
            'description' => 'Burrito con carne asada, pollo, chorizo, arroz, frijoles y todas las salsas.',
            'price' => 110.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 30,
            'status' => 'available',
            'business_id' => $business1->id,
        ]);

        \App\Models\Product::create([
            'name' => 'Burrito Vegetariano',
            'description' => 'Burrito con verduras asadas, arroz, frijoles negros, guacamole y salsa.',
            'price' => 70.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 40,
            'status' => 'available',
            'business_id' => $business1->id,
        ]);

        // Vendor 1 - Tacos y Burritos Don Carlos
        $business2 = \App\Models\Business::where('name', 'Tacos y Burritos Don Carlos')->first();

        \App\Models\Product::create([
            'name' => 'Tacos de Pastor',
            'description' => 'Orden de 3 tacos de pastor con piña, cilantro y cebolla.',
            'price' => 60.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 80,
            'status' => 'available',
            'business_id' => $business2->id,
        ]);

        \App\Models\Product::create([
            'name' => 'Tacos de Carnitas',
            'description' => 'Orden de 3 tacos de carnitas con salsa roja y verde.',
            'price' => 65.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 70,
            'status' => 'available',
            'business_id' => $business2->id,
        ]);

        \App\Models\Product::create([
            'name' => 'Burrito Don Carlos',
            'description' => 'Burrito especial de la casa con carne al pastor y queso.',
            'price' => 90.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 35,
            'status' => 'available',
            'business_id' => $business2->id,
        ]);

        \App\Models\Product::create([
            'name' => 'Quesadilla Grande',
            'description' => 'Quesadilla con tu elección de carne, queso Oaxaca y guacamole.',
            'price' => 55.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 60,
            'status' => 'available',
            'business_id' => $business2->id,
        ]);

        // Vendor 2 - Hamburguesas La Gorda
        $business3 = \App\Models\Business::where('name', 'Hamburguesas La Gorda')->first();

        \App\Models\Product::create([
            'name' => 'Hamburguesa Clásica',
            'description' => 'Hamburguesa con carne 100% res, lechuga, tomate, cebolla y catsup.',
            'price' => 95.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 40,
            'status' => 'available',
            'business_id' => $business3->id,
        ]);

        \App\Models\Product::create([
            'name' => 'Hamburguesa con Queso',
            'description' => 'Hamburguesa con queso cheddar, tocino, aros de cebolla y salsa BBQ.',
            'price' => 115.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 35,
            'status' => 'available',
            'business_id' => $business3->id,
        ]);

        \App\Models\Product::create([
            'name' => 'Hamburguesa Doble',
            'description' => 'Doble carne, doble queso, con todos los vegetales y papas.',
            'price' => 145.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 25,
            'status' => 'available',
            'business_id' => $business3->id,
        ]);

        \App\Models\Product::create([
            'name' => 'Hamburguesa de Pollo',
            'description' => 'Hamburguesa de pollo crujiente con lechuga, tomate y mayonesa.',
            'price' => 105.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 30,
            'status' => 'available',
            'business_id' => $business3->id,
        ]);

        // Vendor 2 - Burger House María
        $business4 = \App\Models\Business::where('name', 'Burger House María')->first();

        \App\Models\Product::create([
            'name' => 'Gourmet Burger',
            'description' => 'Hamburguesa gourmet con carne angus, queso suizo, cebolla caramelizada.',
            'price' => 165.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 20,
            'status' => 'available',
            'business_id' => $business4->id,
        ]);

        \App\Models\Product::create([
            'name' => 'BBQ Bacon Burger',
            'description' => 'Hamburguesa con tocino crujiente, queso y salsa BBQ casera.',
            'price' => 155.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 25,
            'status' => 'available',
            'business_id' => $business4->id,
        ]);

        \App\Models\Product::create([
            'name' => 'Mushroom Swiss Burger',
            'description' => 'Hamburguesa con hongos salteados, queso suizo y ajo.',
            'price' => 150.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 22,
            'status' => 'available',
            'business_id' => $business4->id,
        ]);

        \App\Models\Product::create([
            'name' => 'Veggie Burger',
            'description' => 'Hamburguesa vegetariana con base de frijol, queso y aguacate.',
            'price' => 135.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 18,
            'status' => 'available',
            'business_id' => $business4->id,
        ]);

        // Vendor 3 - Sushi del Pacífico
        $business5 = \App\Models\Business::where('name', 'Sushi del Pacífico')->first();

        \App\Models\Product::create([
            'name' => 'California Roll',
            'description' => 'Roll de cangrejo, aguacate, pepino y semillas de sésamo.',
            'price' => 125.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 30,
            'status' => 'available',
            'business_id' => $business5->id,
        ]);

        \App\Models\Product::create([
            'name' => 'Spicy Tuna Roll',
            'description' => 'Roll de atún picante, pepino y salsa sriracha.',
            'price' => 145.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 25,
            'status' => 'available',
            'business_id' => $business5->id,
        ]);

        \App\Models\Product::create([
            'name' => 'Salmon Nigiri',
            'description' => '4 piezas de nigiri de salmón fresco.',
            'price' => 110.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 35,
            'status' => 'available',
            'business_id' => $business5->id,
        ]);

        \App\Models\Product::create([
            'name' => 'Dragon Roll',
            'description' => 'Roll de langostino, aguacate, cubierto con salmón y eel sauce.',
            'price' => 185.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 20,
            'status' => 'available',
            'business_id' => $business5->id,
        ]);

        // Vendor 3 - Sushi Bar Roberto
        $business6 = \App\Models\Business::where('name', 'Sushi Bar Roberto')->first();

        \App\Models\Product::create([
            'name' => 'Philadelphia Roll',
            'description' => 'Roll de salmón, queso crema y pepino.',
            'price' => 130.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 28,
            'status' => 'available',
            'business_id' => $business6->id,
        ]);

        \App\Models\Product::create([
            'name' => 'Rainbow Roll',
            'description' => 'Roll de california cubierto con pescados variados.',
            'price' => 175.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 22,
            'status' => 'available',
            'business_id' => $business6->id,
        ]);

        \App\Models\Product::create([
            'name' => 'Tempura Roll',
            'description' => 'Roll de langostino tempura, aguacate y salsa especial.',
            'price' => 155.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 24,
            'status' => 'available',
            'business_id' => $business6->id,
        ]);

        \App\Models\Product::create([
            'name' => 'Sashimi Mixto',
            'description' => '12 piezas de sashimi variado (salmón, atún, camarón).',
            'price' => 220.00,
            'image_path' => 'https://via.placeholder.com/300',
            'quantity' => 15,
            'status' => 'available',
            'business_id' => $business6->id,
        ]);
    }
}
