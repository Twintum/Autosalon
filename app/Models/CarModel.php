<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    protected $fillable = [
        'name', 'price', 'photo', 'mark_id', 'transmission', 'drive', 'fuel_tank', 'color', 'mileage', 'year'
    ];

    // Определяем отношение "много к одному" с моделью Mark
    public function mark()
    {
        return $this->belongsTo(Mark::class);
    }
}
