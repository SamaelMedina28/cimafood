<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Reseñas de Ana en negocios donde ya recibió un pedido.
     */
    public function run(): void
    {
        $client = User::where('email', 'ana@cliente.com')->first();

        $reviews = [
            'Burritos El Mexicano' => [5, 'El de carne asada está buenísimo y llegó caliente.'],
            'Hamburguesas La Gorda' => [4, 'Muy rica la doble, aunque tardaron un poco.'],
        ];

        foreach ($reviews as $businessName => [$rating, $comment]) {
            $business = Business::where('name', $businessName)->first();

            Review::create([
                'user_id' => $client->id,
                'business_id' => $business->id,
                'rating' => $rating,
                'comment' => $comment,
            ]);

            $business->refreshRating();
        }
    }
}
