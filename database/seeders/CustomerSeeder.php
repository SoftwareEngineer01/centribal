<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i=0; $i < 5 ; $i++) { 
            DB::table('customers')->insert([          
                'name' => $faker->name,
                'mobile_phone' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'address' => $faker->address,
                'city' => $faker->city,
                'country' => $faker->country,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
