<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pizza extends Model
{
    use HasFactory;

    protected $primaryKey = 'pizza_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'pizza_id',
        'pizza_type_id',
        'size',
        'price',
    ];

    public function type()
    {
        return $this->belongsTo(PizzaType::class, 'pizza_type_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function pizzaType()
    {
        return $this->belongsTo(PizzaType::class, 'pizza_type_id', 'pizza_type_id');
    }
}
