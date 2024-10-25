<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'model', 'price', 'photo', 'mark_id', 'transmission', 'drive', 'fuel_tank', 'color', 'mileage', 'year', 'mark_id', 'discount'
    ];

    public function mark()
    {
        return $this->belongsTo(Mark::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'model_id');
    }
}
