<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitCategory extends Model
{
    use HasFactory;
    protected $table = 'unit_category';

    protected $fillable = [
        'unit_category',
    ];

    // Each unit should belong in its own category
    public function unit(){
        return $this->hasMany(Unit::class);
    }

    public function image(){
        return $this->hasMany(image::class);
    }
}