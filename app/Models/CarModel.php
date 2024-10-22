<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    protected $fillable = [
        'model', 'price', 'photo', 'mark_id', 'transmission', 'drive', 'fuel_tank', 'color', 'mileage', 'year', 'mark_id', 'discount'
    ];

    public function mark()
    {
        return $this->belongsTo(Mark::class);
    }
}
