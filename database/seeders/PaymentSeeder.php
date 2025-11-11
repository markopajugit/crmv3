<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = Order::all();

        if ($orders->isEmpty()) {
            $this->command->warn('Skipping PaymentSeeder: No orders found.');
            return;
        }

        foreach ($orders->random(min(80, $orders->count())) as $order) {
            $paymentCount = rand(1, 3);
            
            for ($i = 0; $i < $paymentCount; $i++) {
                try {
                    $sum = fake()->randomFloat(2, 50, 5000);
                    $paidDate = fake()->dateTimeBetween('-1 year', 'now');
                    
                    Payment::create([
                        'order_id' => $order->id,
                        'type' => fake()->randomElement(['bank_transfer', 'card', 'cash', 'other']),
                        'sum' => $sum,
                        'details' => fake()->optional()->sentence(rand(5, 15)),
                        'paid_date' => $paidDate,
                        'created_at' => $paidDate,
                        'updated_at' => $paidDate,
                    ]);
                } catch (\Exception $e) {
                    // Skip on any constraint errors
                    continue;
                }
            }
        }
    }
}

