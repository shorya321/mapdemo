<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_number',
        'customer_id',
        'vehicle_id',
        'pickup_date',
        'return_date',
        'pickup_location',
        'return_location',
        'total_days',
        'base_price',
        'extra_charges',
        'tax_amount',
        'total_amount',
        'payment_status',
        'booking_status',
        'cancellation_reason',
        'notes'
    ];

    public function payments()
    {
        return $this->hasMany(BookingPayment::class);
    }

    public function extras()
    {
        return $this->hasMany(BookingExtra::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
