<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ReminderService;
use App\Models\Reminder;
use Illuminate\Support\Carbon;

class ScanRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:scan-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan clients for upcoming events and prepare reminders';

    /**
     * Execute the console command.
     */
    public function handle(ReminderService $reminderService)
    {
        $this->info('Starting Reminder Scan for ' . Carbon::today()->format('Y-m-d') . '...');
        
        $today = Carbon::today();
        
        $birthdays = $reminderService->getUpcomingBirthdays($today, $today);
        $anniversaries = $reminderService->getUpcomingAnniversaries($today, $today);
        $dueSoon = $reminderService->getUpcomingPremiumDues($today, $today->copy()->addDays(7));

        $this->info('-----------------------------------');
        $this->info("Birthdays Today: " . $birthdays->count());
        foreach ($birthdays as $client) {
            $this->line(" - {$client->name} ({$client->phone})");
            Reminder::firstOrCreate(
                [
                    'client_id' => $client->id,
                    'type' => 'birthday',
                    'event_date' => Carbon::parse($client->birthday)->setYear($today->year),
                    'reminder_date' => $today,
                ],
                [
                    'title' => "{$client->name}'s Birthday",
                    'status' => Reminder::STATUS_PENDING,
                    'source' => 'system'
                ]
            );
        }

        $this->info("Anniversaries Today: " . $anniversaries->count());
        foreach ($anniversaries as $client) {
            $this->line(" - {$client->name} ({$client->phone})");
            Reminder::firstOrCreate(
                [
                    'client_id' => $client->id,
                    'type' => 'anniversary',
                    'event_date' => Carbon::parse($client->anniversary_date)->setYear($today->year),
                    'reminder_date' => $today,
                ],
                [
                    'title' => "{$client->name}'s Anniversary",
                    'status' => Reminder::STATUS_PENDING,
                    'source' => 'system'
                ]
            );
        }

        $this->info("Premium Dues (Next 7 days): " . $dueSoon->count());
        foreach ($dueSoon as $client) {
            $this->line(" - {$client->name} ({$client->premium_due_date->format('M j')})");
            
            // Calculate actual upcoming due date (set to this year, or next if it already passed this year)
            $eventDate = Carbon::parse($client->premium_due_date)->setYear($today->year);
            if ($eventDate->isBefore($today)) {
                $eventDate->addYear();
            }

            Reminder::firstOrCreate(
                [
                    'client_id' => $client->id,
                    'type' => 'premium_due',
                    'event_date' => $eventDate,
                    'reminder_date' => $today,
                ],
                [
                    'title' => "{$client->name}'s Premium Due",
                    'status' => Reminder::STATUS_PENDING,
                    'source' => 'system'
                ]
            );
        }
        $this->info('-----------------------------------');
        
        $this->info('Scan complete! Pending records have been safely generated in the reminders table without duplicates.');
    }
}
