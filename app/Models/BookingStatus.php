<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingStatus extends Model
{
    use HasFactory;
    protected $protected = 'bookingStatus';

    protected $fillable = [
        'date_booked',
        'user_id',
        'is_booked',
        'number_of_days_booked'
    ];

    public function unit(){
        return $this->belongsTo(Unit::class);
    }
}
