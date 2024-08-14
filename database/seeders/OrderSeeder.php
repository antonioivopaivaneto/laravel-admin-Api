<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cria 30 instâncias de Order e itera sobre cada uma delas
        $orders = Order::factory()->count(30)->create();

        foreach ($orders as $order) {
            // Gera um número aleatório entre 1 e 5 para a quantidade de OrderItems
            $itemCount = rand(1, 5);

            // Cria OrderItems com o order_id correspondente
            OrderItem::factory()->count($itemCount)->create([
                'order_id' => $order->id,
            ]);
        }
    }
}
