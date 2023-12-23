<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create a default admin user
        User::create([
            'name' => 'SiATEX',
            'email' => 'siatex@siatex.com',
            'password' => Hash::make('1qazxsw2'), // Hash the password
        ]);
        // Create a default admin user
        User::create([
            'name' => 'Pritom',
            'email' => 'pritom@siatex.com',
            'password' => Hash::make('pritom@#'), // Hash the password
        ]);
        // Create additional users if needed
        //User::factory(10)->create(); // Creates 10 random users using a factory
    }
}
