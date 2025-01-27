<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Vehicle::factory(500)->create();
        // $this->call([
        //     VehicleSeeder::class
        // ]);
    }
}