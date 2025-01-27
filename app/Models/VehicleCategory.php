<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleCategory extends Model
{
    use HasFactory;

    // Specify the table name if it's not plural by default
    protected $table = 'vehicle_categories';

    // Define fillable fields for mass assignment
    protected $fillable = [
        'name', 
        'slug', 
        'description', 
        'icon', 
        'status',
    ];

    // Optional: Add any methods for this model like generating slugs or formatting descriptions.

    // Scope to get active categories
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // You can define other relationships or helper methods here if needed.
}
