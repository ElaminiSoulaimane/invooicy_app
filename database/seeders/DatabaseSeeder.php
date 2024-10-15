<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Clients Seeder
        for ($i = 0; $i < 100; $i++) {
            DB::table('clients')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Products Seeder
        for ($i = 0; $i < 100; $i++) {
            DB::table('products')->insert([
                'name' => $faker->words(3, true),
                'price' => $faker->randomFloat(2, 10, 1000),
                'buy_price' => $faker->randomFloat(2, 10, 100),

                'quantity' => $faker->numberBetween(0, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Services Seeder
        for ($i = 0; $i < 100; $i++) {
            DB::table('services')->insert([
                'name' => $faker->words(2, true) . ' Service',
                'price' => $faker->randomFloat(2, 50, 500),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
