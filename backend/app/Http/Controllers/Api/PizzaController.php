<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pizza;
use Illuminate\Http\Request;

class PizzaController extends Controller
{
    public function index()
    {
        return response()->json(Pizza::all());
    }
}
