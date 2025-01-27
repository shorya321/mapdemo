<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingPayment extends Model
{
    protected $fillable = [
        'booking_id',
        'payment_method',
        'transaction_id',
        'amount',
        'payment_status',
        'payment_date'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
