<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProfile extends Model
{
    use HasFactory;

    // Specify the table name if it's not plural by default
    protected $table = 'vendor_profiles';

    // Define fillable fields for mass assignment
    protected $fillable = [
        'user_id', 
        'company_name', 
        'company_phone_number', 
        'company_email', 
        'company_address', 
        'company_gst_number', 
        'status',
    ];

    // Relationship: A vendor profile belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // You can define other relationships or helper methods here if needed.
}
