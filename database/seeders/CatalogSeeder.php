<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogSeeder extends Seeder
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
            DB::table('catalogs')->insert([          
                'name' => $faker->text(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
