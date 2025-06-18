<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_details_id',
        'id',
        'pizza_id',
        'quantity',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id', 'id');
    }

    public function pizza()
    {
        return $this->belongsTo(Pizza::class, 'pizza_id', 'pizza_id');
    }
}
