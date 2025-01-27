<?php

namespace Database\Factories;

use App\Models\Vehicle;
use App\Models\VendorProfile;
use App\Models\VehicleCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition()
    {
        // Fetch all vendor_ids from the vendor_profiles table
        $vendorIds = VendorProfile::all()->pluck('user_id')->toArray();

        // Fetch all category_ids from the vehicle_categories table
        $categoryIds = VehicleCategory::all()->pluck('id')->toArray();

        $jsonPath = database_path('data/countries.json');
        
        // Read and decode the JSON data
        $countriesData = json_decode(file_get_contents($jsonPath), true);

        // $indiaLocations = array_filter($countriesData, function ($location) {
        //     return isset($location['State']) && strtolower($location['country']) === 'india';
        // });
        $location = $this->faker->randomElement($countriesData);
        return [
            // Pick a random vendor_id from the list of all vendor IDs
            'vendor_id' => $this->faker->randomElement($vendorIds),

            // Pick a random category_id from the list of all category IDs
            'category_id' => $this->faker->randomElement($categoryIds),

            'seating_capacity' => $this->faker->numberBetween(2, 7),
            'brand' => $this->faker->company,
            'model' => $this->faker->word,
            'number_of_doors' => $this->faker->numberBetween(2, 5),
            'transmission' => $this->faker->randomElement(['manual', 'automatic']),
            'luggage_capacity' => $this->faker->numberBetween(50, 500),
            'horsepower' => $this->faker->numberBetween(50, 500),
            'fuel' => $this->faker->randomElement(['petrol', 'diesel', 'electric', 'hybrid']),
            'co2' => $this->faker->numberBetween(100, 300),
            'color' => $this->faker->colorName,
            'mileage' => $this->faker->numberBetween(5, 30),
            'location' => $location['State'] ?? 'Unknown',
            'latitude' =>  $location['Lat'] ?? 0, 
            'longitude' => $location['Long'] ?? 0,
            'status' => $this->faker->randomElement(['available', 'rented', 'maintenance']),
            'features' => $this->faker->optional()->words(5),
            'featured' => $this->faker->boolean,
            'security_deposit' => $this->faker->randomFloat(2, 1000, 5000),
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal', 'bank_transfer']),
            'price_per_day' => $this->faker->randomFloat(2, 50, 500),
        ];
    }
}
