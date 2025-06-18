<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderDetailsController extends Controller
{
    public function index()
    {
        return response()->json(OrderDetail::with('order', 'pizza')->paginate(10));
    }

    public function total_sales()
    {
        $total = OrderDetail::join('pizzas', 'order_details.pizza_id', '=', 'pizzas.pizza_id')
            ->selectRaw('SUM(order_details.quantity * pizzas.price) as total_sales')
            ->value('total_sales');

        return response()->json(['total_sales' => round($total, 2)]);
    }

    public function top_pizzas()
    {
        $top = OrderDetail::select('pizza_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('pizza_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        return response()->json($top);
    }
}
