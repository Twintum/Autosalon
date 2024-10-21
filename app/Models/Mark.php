<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    protected $fillable = ['name', 'photo'];

    public function models()
    {
        return $this->hasMany(CarModel::class);
    }
}
