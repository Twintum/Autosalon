<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
      'model_id',
      'user_id',
      'status'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'model_id');
    }
}
