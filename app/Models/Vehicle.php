<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    // Define the table name (optional, if it is different from the default 'vehicles')
    protected $table = 'vehicles';

    // Define the fillable properties
    protected $fillable = [
        'vendor_id',
        'category_id',
        'seating_capacity',
        'brand',
        'model',
        'number_of_doors',
        'transmission',
        'luggage_capacity',
        'horsepower',
        'fuel',
        'co2',
        'color',
        'mileage',
        'location',
        'latitude',
        'longitude',
        'status',
        'features',
        'featured',
        'security_deposit',
        'payment_method',
        'price_per_day',
    ];

    // Cast the 'features' attribute to an array, since it's a JSON column
    protected $casts = [
        'features' => 'array',
    ];

    // Define the relationship with the VendorProfile model
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');  // Assuming 'user_id' from 'vendor_profiles'
    }

    // Define the relationship with the VehicleCategory model
    public function category()
    {
        return $this->belongsTo(VehicleCategory::class, 'category_id');
    }
}