<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class amenities extends Model
{
    use HasFactory;

    protected $table = 'amenities';

    protected $fillable = [
        'amenities',
        'unit_id'
    ];
    
    protected function unit(){
        return $this->belongsTo(User::class);
    }
}
