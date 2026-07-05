<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reminder;
use App\Jobs\SendReminderMessageJob;
use Illuminate\Support\Carbon;

class DispatchPendingRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches pending reminders to the delivery queue.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Scanning for pending reminders to dispatch...");

        // Fetch reminders that are pending and due today (or previously missed)
        $reminders = Reminder::where('status', Reminder::STATUS_PENDING)
            ->whereDate('reminder_date', '<=', Carbon::today())
            ->get();

        if ($reminders->isEmpty()) {
            $this->info("No pending reminders to dispatch.");
            return;
        }

        foreach ($reminders as $reminder) {
            // Immediately mark as processing to prevent race conditions if the job runs long
            $reminder->update(['status' => Reminder::STATUS_PROCESSING]);

            // Dispatch to the queue
            SendReminderMessageJob::dispatch($reminder);

            $this->line("Dispatched Job for Reminder ID: {$reminder->id}");
        }

        $this->info("Successfully dispatched {$reminders->count()} reminders to the queue.");
    }
}
