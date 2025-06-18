<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PizzaType;
use Illuminate\Http\Request;

class PizzaTypeController extends Controller
{
    public function index()
    {
        return response()->json(PizzaType::all());
    }
}
