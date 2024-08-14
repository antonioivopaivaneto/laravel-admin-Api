<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::paginate(10);

        return OrderResource::collection($orders);

    }

    public function show($id)
    {
        return new  OrderResource(Order::find($id));

    }
}
