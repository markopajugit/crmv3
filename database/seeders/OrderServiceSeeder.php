<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderService;
use App\Models\Service;
use Illuminate\Database\Seeder;

class OrderServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // First, update existing order services with 0 cost
        $this->updateZeroCostServices();
        
        $orders = Order::all();
        $services = Service::all();

        if ($orders->isEmpty()) {
            $this->command->warn('No orders found. Please seed orders first.');
            return;
        }

        if ($services->isEmpty()) {
            $this->command->warn('No services found. Please seed services first.');
            return;
        }

        $this->command->info("Found {$orders->count()} orders and {$services->count()} services.");

        // Check how many orders already have services
        $ordersWithServices = Order::whereHas('orderServices')->count();
        $this->command->info("{$ordersWithServices} orders already have services attached.");

        // Get orders without services
        $ordersWithoutServices = Order::whereDoesntHave('orderServices')->get();
        
        if ($ordersWithoutServices->isEmpty()) {
            $this->command->info('All orders already have services attached.');
            return;
        }

        $this->command->info("Attaching services to {$ordersWithoutServices->count()} orders without services...");

        $attachedCount = 0;
        $serviceCount = 0;

        foreach ($ordersWithoutServices as $order) {
            // Randomly decide if this order should have services (70% chance)
            // Or you can set a specific number: if ($attachedCount >= 200) break;
            if (rand(1, 100) > 30) {
                // Attach 1-3 random services to each order
                $servicesToAttach = $services->random(min(rand(1, 3), $services->count()));
                
                foreach ($servicesToAttach as $service) {
                    // Skip if this service is already attached to this order
                    if (OrderService::where('order_id', $order->id)
                        ->where('service_id', $service->id)
                        ->exists()) {
                        continue;
                    }

                    $dateFrom = null;
                    $dateTo = null;

                    // Handle recurring services
                    if ($service->type == 'Reaccuring' && $service->reaccuring_frequency) {
                        $dateFrom = date('d.m.Y');
                        if ($service->reaccuring_frequency == 3) {
                            $dateTo = date('d.m.Y', strtotime('+3 months -1 days'));
                        } elseif ($service->reaccuring_frequency == 6) {
                            $dateTo = date('d.m.Y', strtotime('+6 months -1 days'));
                        } elseif ($service->reaccuring_frequency == 12) {
                            $dateTo = date('d.m.Y', strtotime('+1 year -1 days'));
                        }
                    }

                    // Use service cost if it's set and greater than 0, otherwise generate a realistic cost
                    $cost = $service->cost ?? '0';
                    if (empty($cost) || (float) $cost == 0) {
                        // Generate realistic costs based on service type
                        if ($service->type == 'Reaccuring') {
                            // Recurring services: €50-500 per period
                            $cost = (string) rand(5000, 50000) / 100; // €50.00 - €500.00
                        } else {
                            // Regular services: €25-250
                            $cost = (string) rand(2500, 25000) / 100; // €25.00 - €250.00
                        }
                    }

                    OrderService::create([
                        'order_id' => $order->id,
                        'service_id' => $service->id,
                        'name' => $service->name,
                        'cost' => $cost,
                        'date_from' => $dateFrom,
                        'date_to' => $dateTo,
                        'renewed' => false,
                    ]);

                    $serviceCount++;
                }

                $attachedCount++;
            }
        }

        $this->command->info("Successfully attached {$serviceCount} services to {$attachedCount} orders.");
        
        // Also sync the belongsToMany relationship for consistency
        $this->command->info('Syncing belongsToMany relationships...');
        $syncedCount = 0;
        foreach (Order::with('orderServices')->get() as $order) {
            if ($order->orderServices->isNotEmpty()) {
                $serviceIds = $order->orderServices->pluck('service_id')->toArray();
                $order->services()->sync($serviceIds);
                $syncedCount++;
            }
        }
        $this->command->info("Synced {$syncedCount} orders with belongsToMany relationship.");
    }

    /**
     * Update existing order services that have 0 cost
     *
     * @return void
     */
    private function updateZeroCostServices()
    {
        // Get order services with 0 or empty cost
        $zeroCostServices = OrderService::where(function($query) {
            $query->where('cost', '0')
                  ->orWhere('cost', '')
                  ->orWhereNull('cost');
        })->with('order')->get();

        if ($zeroCostServices->isEmpty()) {
            $this->command->info('No order services with zero cost found.');
            return;
        }

        $this->command->info("Found {$zeroCostServices->count()} order services with zero cost. Updating...");

        $updatedCount = 0;
        foreach ($zeroCostServices as $orderService) {
            // Try to get the service to check its type
            $service = Service::find($orderService->service_id);
            
            // Generate realistic cost based on service type
            if ($service && $service->type == 'Reaccuring') {
                // Recurring services: €50-500 per period
                $cost = (string) rand(5000, 50000) / 100; // €50.00 - €500.00
            } else {
                // Regular services: €25-250
                $cost = (string) rand(2500, 25000) / 100; // €25.00 - €250.00
            }

            $orderService->update(['cost' => $cost]);
            $updatedCount++;
        }

        $this->command->info("Updated {$updatedCount} order services with realistic costs.");
    }
}
