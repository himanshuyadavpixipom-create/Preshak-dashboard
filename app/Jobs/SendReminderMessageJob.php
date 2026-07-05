<?php

namespace App\Jobs;

use App\Models\Reminder;
use App\Services\MessageDispatchService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReminderMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    /**
     * Calculate the number of seconds to wait before retrying the job.
     *
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [60, 180, 600];
    }

    protected Reminder $reminder;

    /**
     * Create a new job instance.
     */
    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    /**
     * Execute the job.
     */
    public function handle(MessageDispatchService $dispatchService): void
    {
        // Re-check state: if it's already sent, don't send again.
        if ($this->reminder->status === Reminder::STATUS_SENT || $this->reminder->status === Reminder::STATUS_CANCELLED) {
            return;
        }

        $dispatchService->dispatch($this->reminder);
    }
}
