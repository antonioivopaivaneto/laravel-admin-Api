<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChartResource;
use App\Models\Order;
use Gate;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function chart()
    {
        Gate::authorize('view','orders');

        $orders = Order::query()
        ->join('order_items','orders.id','=','order_items.order_id')
        ->selectRaw("DATA_FORMAT(orders.created_at as, '%d-%m-%Y') date, sum(order_items.quantity*order_items.price) as sum")
        ->groupBy('date')
        ->get();

        return  ChartResource::collection($orders);
    }
}
