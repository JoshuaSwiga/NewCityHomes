<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $table = 'units';

    protected $fillable =[
        'title',
        'subtitle',
        'userThatUploaded',
        'category',    
        'accomodation_information',
        'number_of_bedrooms',
        'number_of_bathrooms',
        'price_information',    
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }

    // Each Unit will have one location
    public function location(){
        return $this->belongsTo(Location::class);
    }

    public function images(){
        return $this->hasMany(image::class);
    }

    public function bookingStatus(){
        return $this->hasOne(BookingStatus::class);
    }

    public function amenities(){
        return $this->hasOne(amenities::class);
    }

}