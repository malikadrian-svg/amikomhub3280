<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateEventStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-event-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates event statuses automatically based on their start and end dates.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        // 1. Transition 'approved' to 'active' if start_date has passed
        $activated = Event::where('status', 'approved')
            ->where('start_date', '<=', $now)
            ->update(['status' => 'active']);

        // 2. Transition 'active' to 'completed' if end_date has passed
        $completed = Event::where('status', 'active')
            ->where('end_date', '<=', $now)
            ->update(['status' => 'completed']);

        $this->info("Updated {$activated} events to active.");
        $this->info("Updated {$completed} events to completed.");
        
        Log::info('Event statuses updated automatically.', [
            'activated' => $activated,
            'completed' => $completed,
        ]);
    }
}
