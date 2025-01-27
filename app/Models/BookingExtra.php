<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingExtra extends Model
{
    protected $fillable = [
        'booking_id',
        'extra_type',
        'extra_name',
        'quantity',
        'price'
    ];
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
