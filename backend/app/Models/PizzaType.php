<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PizzaType extends Model
{
    use HasFactory;
    protected $primaryKey = 'pizza_type_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'pizza_type_id',
        'category',
        'name',
        'ingredients',
    ];

    public function pizzas()
    {
        return $this->hasMany(Pizza::class, 'pizza_type_id');
    }
}
