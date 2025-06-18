<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $orders = Order::with('items.pizza.type')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('id', '=', $search);
                });
            })
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('date', '<=', $endDate);
            })
            ->orderBy('date', 'asc')
            ->paginate(10);

        return response()->json($orders);
    }

    public function metrics()
    {
        $totalOrders = Order::count();
        $totalQuantity = OrderDetail::sum('quantity');
        $revenue = DB::table('order_details')
            ->join('pizzas', 'order_details.pizza_id', '=', 'pizzas.pizza_id')
            ->selectRaw('SUM(order_details.quantity * pizzas.price) as total_revenue')
            ->value('total_revenue');


        $top = OrderDetail::with('pizza.pizzaType')
            ->select('pizza_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('pizza_id')
            ->orderByDesc('total_quantity')
            ->first();

        return response()->json([
            'total_orders' => $totalOrders,
            'total_quantity' => $totalQuantity,
            'revenue' => $revenue,
            'top_pizza' => $top->pizza?->pizzaType?->name ?? 'Unknown'
        ]);
    }
}
