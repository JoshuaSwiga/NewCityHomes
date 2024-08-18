<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $table = 'locations';

    protected $fillable=[
        'city',
        'state',
        'country', 
        'unit_id',
               
    ];

    public function unit(){
        return $this->hasOne(Unit::class);
    }
}
