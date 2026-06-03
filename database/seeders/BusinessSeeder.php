<?php

namespace Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vendor 1 (Carlos Rodríguez) - Burritos
        $vendor1 = \App\Models\User::where('email', 'test@example.com')->first();

        \App\Models\Business::create([
            'name' => 'Burritos El Mexicano',
            'description' => 'Los mejores burritos auténticos de la ciudad con ingredientes frescos y salsas caseras.',
            'phone' => '5512345678',
            'logo' => asset('example_images/businesses/logo/burrito.jpg'),
            'banner' => asset('example_images/businesses/banner/burrito.jpg'),
            'status' => 'active',
            'rating' => 4.5,
            'open_time' => '10:00',
            'close_time' => '22:00',
            'user_id' => $vendor1->id,
        ]);

        \App\Models\Business::create([
            'name' => 'Tacos y Burritos Don Carlos',
            'description' => 'Especialidad en tacos y burritos con la tradición familiar.',
            'phone' => '5512345678',
            'logo' => asset('example_images/businesses/logo/tacos.jpg'),
            'banner' => asset('example_images/businesses/banner/tacos.jpg'),
            'status' => 'active',
            'rating' => 4.2,
            'open_time' => '09:00',
            'close_time' => '23:00',
            'user_id' => $vendor1->id,
        ]);

        // Vendor 2 (María González) - Hamburguesas
        $vendor2 = \App\Models\User::where('email', 'maria@hamburguesas.com')->first();

        \App\Models\Business::create([
            'name' => 'Hamburguesas La Gorda',
            'description' => 'Hamburguesas jugosas con carne 100% res y pan artesanal.',
            'phone' => '5523456789',
            'logo' => asset('example_images/businesses/logo/hamburgesa.jpg'),
            'banner' => asset('example_images/businesses/banner/hamburgesa.jpg'),
            'status' => 'active',
            'rating' => 4.7,
            'open_time' => '11:00',
            'close_time' => '23:00',
            'user_id' => $vendor2->id,
        ]);

        \App\Models\Business::create([
            'name' => 'Burger House María',
            'description' => 'Casa de hamburguesas gourmet con ingredientes premium.',
            'phone' => '5523456789',
            'logo' => asset('example_images/businesses/logo/hamburgesa2.jpg'),
            'banner' => asset('example_images/businesses/banner/hamburgesa2.jpg'),
            'status' => 'active',
            'rating' => 4.4,
            'open_time' => '12:00',
            'close_time' => '00:00',
            'user_id' => $vendor2->id,
        ]);

        // Vendor 3 (Roberto Martínez) - Sushi
        $vendor3 = \App\Models\User::where('email', 'roberto@sushi.com')->first();

        \App\Models\Business::create([
            'name' => 'Sushi del Pacífico',
            'description' => 'Sushi fresco del día con ingredientes importados de Japón.',
            'phone' => '5534567890',
            'logo' => asset('example_images/businesses/logo/sushi.jpg'),
            'banner' => asset('example_images/businesses/banner/sushi.jpg'),
            'status' => 'active',
            'rating' => 4.8,
            'open_time' => '12:00',
            'close_time' => '22:00',
            'user_id' => $vendor3->id,
        ]);

        \App\Models\Business::create([
            'name' => 'Sushi Bar Roberto',
            'description' => 'Bar de sushi con rolls creativos y platos tradicionales.',
            'phone' => '5534567890',
            'logo' => asset('example_images/businesses/logo/sushi2.jpg'),
            'banner' => asset('example_images/businesses/banner/sushi2.jpg'),
            'status' => 'active',
            'rating' => 4.6,
            'open_time' => '13:00',
            'close_time' => '21:00',
            'user_id' => $vendor3->id,
        ]);
    }
}
