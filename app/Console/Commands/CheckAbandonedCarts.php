<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\NotificationLog;
use App\Notifications\AbandonedCartNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class CheckAbandonedCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:check-abandoned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for abandoned carts and send WhatsApp reminders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting abandoned cart check...');

        // 1. 30 Minutes Reminder
        $thirtyMinsAgo = Carbon::now()->subMinutes(30);
        $this->processReminders('30m', $thirtyMinsAgo, Carbon::now()->subHours(6));

        // 2. 6 Hours Reminder
        $sixHoursAgo = Carbon::now()->subHours(6);
        $this->processReminders('6h', $sixHoursAgo, Carbon::now()->subHours(24));

        // 3. 24 Hours Reminder (Almost Expired)
        $twentyFourHoursAgo = Carbon::now()->subHours(24);
        $this->processReminders('24h', $twentyFourHoursAgo, Carbon::now()->subHours(48));

        $this->info('Abandoned cart check completed.');
    }

    protected function processReminders($type, $startThreshold, $endThreshold)
    {
        // Find pending orders created older than startThreshold but newer than endThreshold
        // and have not been notified for this specific type yet.
        $orders = Order::where('status', 'pending')
            ->where('created_at', '<=', $startThreshold)
            ->where('created_at', '>', $endThreshold)
            ->whereDoesntHave('notificationLogs', function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->get();

        foreach ($orders as $order) {
            try {
                // Determine destination
                $phone = $order->customer_phone;
                if (empty($phone)) continue;

                // Send Notification anonymously
                Notification::route('whatsapp', $phone)
                    ->notify(new AbandonedCartNotification($order, $type));

                $this->info("Sent {$type} reminder for Order ID {$order->id}");
            } catch (\Exception $e) {
                Log::error("Failed to send {$type} abandoned cart reminder for Order {$order->id}: " . $e->getMessage());
            }
        }
    }
}
