<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Canned;
use Faker\Factory as Faker;

class CannedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create an instance of Faker
        $faker = Faker::create();

        // Insert 10 random canned hints
        for ($i = 0; $i < 100; $i++) {
            Canned::create([
                'title' => $faker->sentence(3), // Generates a random sentence with 3 words
                'content' => $faker->paragraph(4), // Generates a random paragraph with 4 sentences
                'type' => $faker->randomElement(['hint', 'important', 'alert']), // Randomly choose one of the types
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
