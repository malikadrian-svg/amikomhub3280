<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Notifications\ReviewReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendReviewReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Run daily by the scheduler. Can also be run manually:
     *   php artisan review:remind
     */
    protected $signature = 'review:remind
                            {--dry-run : Preview who would be notified without sending}';

    protected $description = 'Send in-app review reminder notifications to attendees of events that ended exactly 1 day ago.';

    /**
     * Execute the console command.
     *
     * Logic:
     *  1. Find events whose date was between yesterday 00:00 and yesterday 23:59
     *     (i.e., events that ended exactly 1 day ago — now eligible for review)
     *  2. For each event, find all users with a paid transaction
     *  3. Skip users who already reviewed the event
     *  4. Send a ReviewReminderNotification to remaining users
     */
    public function handle(): int
    {
        $yesterday = Carbon::yesterday();

        $events = Event::whereBetween('date', [
            $yesterday->copy()->startOfDay(),
            $yesterday->copy()->endOfDay(),
        ])->get();

        if ($events->isEmpty()) {
            $this->info('No events ended yesterday. No notifications to send.');
            return self::SUCCESS;
        }

        $this->info("Found {$events->count()} event(s) eligible for review reminders.");

        $totalSent = 0;
        $totalSkipped = 0;

        foreach ($events as $event) {
            $this->line("  → Processing: {$event->title}");

            // Get all users with a paid transaction for this event
            $eligibleUsers = \App\Models\User::whereHas('transactions', function ($q) use ($event) {
                $q->where('event_id', $event->id)
                  ->whereIn('status', ['success', 'settlement', 'capture']);
            })
            // Exclude users who already submitted a review
            ->whereDoesntHave('reviews', function ($q) use ($event) {
                $q->where('event_id', $event->id);
            })
            // Exclude users who already received this reminder
            ->whereDoesntHave('notifications', function ($q) use ($event) {
                $q->where('type', ReviewReminderNotification::class)
                  ->whereJsonContains('data->event_id', $event->id);
            })
            ->get();

            if ($eligibleUsers->isEmpty()) {
                $this->line("     ↳ No eligible users (all already reviewed or notified).");
                continue;
            }

            if ($this->option('dry-run')) {
                $this->warn("     ↳ [DRY RUN] Would notify {$eligibleUsers->count()} user(s):");
                $eligibleUsers->each(fn ($u) => $this->line("       - {$u->name} ({$u->email})"));
                $totalSent += $eligibleUsers->count();
                continue;
            }

            foreach ($eligibleUsers as $user) {
                $user->notify(new ReviewReminderNotification($event));
                $totalSent++;
            }

            $this->line("     ↳ Notified {$eligibleUsers->count()} user(s).");
        }

        $this->info("Done. Sent: {$totalSent} | Skipped: {$totalSkipped}");

        return self::SUCCESS;
    }
}
