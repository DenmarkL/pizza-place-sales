<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'date',
        'time',
    ];

    public function items()
    {
        return $this->hasMany(OrderDetail::class, 'id', 'id');
    }
}
