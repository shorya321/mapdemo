<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    public function run()
    {
        $this->call(VehicleSeeder::class);
    //     // Mumbai locations
    //     $this->createVehicle('Mumbai Central', 18.9691, 72.8193);
    //     $this->createVehicle('Bandra West', 19.0596, 72.8295);
    //     $this->createVehicle('Andheri East', 19.1136, 72.8697);
        
    //     // Delhi locations
    //     $this->createVehicle('Connaught Place', 28.6289, 77.2065);
    //     $this->createVehicle('Karol Bagh', 28.6619, 77.1921);
        
    //     // Bangalore locations
    //     $this->createVehicle('MG Road', 12.9716, 77.6189);
    //     $this->createVehicle('Indiranagar', 12.9784, 77.6408);
    // }

    // private function createVehicle($location, $lat, $lng)
    // {
    //     $brands = ['Toyota', 'Honda', 'Hyundai', 'Maruti', 'Tata'];
    //     $models = ['SUV', 'Sedan', 'Hatchback', 'Premium'];
        
    //     Vehicle::create([
    //         'name' => $brands[array_rand($brands)] . ' ' . $models[array_rand($models)],
    //         'model' => '2023',
    //         'year' => rand(2020, 2024),
    //         'latitude' => $lat,
    //         'longitude' => $lng,
    //         'address' => "Sample Address, $location",
    //         'city' => explode(',', $location)[0],
    //         'state' => $this->getState($lat, $lng),
    //         'country' => 'India',
    //         'price_per_day' => rand(2000, 5000),
    //         'is_available' => true,
    //         'image_url' => 'https://via.placeholder.com/300x200'
    //     ]);
    // }

    // private function getState($lat, $lng)
    // {
    //     if ($lat > 28) return 'Delhi';
    //     if ($lat > 18) return 'Maharashtra';
    //     return 'Karnataka';
    }
}