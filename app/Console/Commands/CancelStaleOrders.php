<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class CancelStaleOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-stale';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel orders that were not paid within 2 minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredOrders = Order::where('status', 'pending')->where('created_at', '<=', Carbon::now()->subMinutes(2))->get();

        foreach ($expiredOrders as $order) {
            $order->update(['status' => 'cancelled']);
            $this->info("Cancelled order ID: {$order->id}");
        }

        return Command::SUCCESS;
    }
}
