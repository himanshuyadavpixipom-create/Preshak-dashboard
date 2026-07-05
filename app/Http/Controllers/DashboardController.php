<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReminderService;
use App\Models\Client;
use App\Models\Festival;
use App\Models\Reminder;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(ReminderService $reminderService)
    {
        $today = Carbon::today();
        
        // Month boundaries for calendar
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();
        
        // Fetch Stats
        $totalClients = Client::count();
        $messagesSent = \App\Models\DeliveryLog::where('status', 'sent')->count();
        $pendingReminders = Reminder::where('status', 'pending')->count();
        $failedDeliveries = \App\Models\DeliveryLog::where('status', 'failed')->count();

        // Fetch Month Events (Reminders & Festivals)
        $monthReminders = Reminder::with('client')
            ->whereBetween('reminder_date', [$startOfMonth, $endOfMonth])
            ->get();
            
        $monthFestivals = Festival::whereBetween('festival_date', [$startOfMonth, $endOfMonth])
            ->get();

        // Build Calendar Array
        $calendarDays = [];
        $startGrid = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endGrid = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);
        
        for ($date = $startGrid->copy(); $date->lte($endGrid); $date->addDay()) {
            $dateString = $date->format('Y-m-d');
            $isCurrentMonth = $date->month === $today->month;
            $isToday = $date->isToday();
            
            $dayEvents = [];
            
            foreach ($monthReminders as $rem) {
                if ($rem->reminder_date->format('Y-m-d') === $dateString) {
                    $clientName = $rem->client->name ?? 'Unknown';
                    $typeFormatted = str_replace('_', ' ', ucfirst($rem->type));
                    
                    $details = '';
                    if ($rem->type === 'premium_due' && $rem->client) {
                        $details = 'Policy: ' . ($rem->client->policy_name ?? 'N/A') . ' (' . ($rem->client->policy_number ?? 'N/A') . ')';
                    } elseif ($rem->type === 'anniversary') {
                        $details = 'Wedding Anniversary';
                    } else {
                        $details = 'Date of Birth';
                    }

                    $dayEvents[] = [
                        'id' => 'rem_'.$rem->id,
                        'type' => 'reminder',
                        'title' => $clientName . ' ' . ucwords($typeFormatted),
                        'details' => $details,
                        'color' => 'bg-accent-500'
                    ];
                }
            }
            
            foreach ($monthFestivals as $fest) {
                if ($fest->festival_date->format('Y-m-d') === $dateString) {
                    $dayEvents[] = [
                        'id' => 'fest_'.$fest->id,
                        'type' => 'festival',
                        'title' => $fest->name,
                        'details' => 'Festival / Holiday',
                        'color' => 'bg-violet-500'
                    ];
                }
            }
            
            $calendarDays[] = [
                'date' => $date->copy(),
                'day' => $date->day,
                'isCurrentMonth' => $isCurrentMonth,
                'isToday' => $isToday,
                'events' => $dayEvents,
            ];
        }

        // Upcoming stuff for the widget
        $upcomingFestivals = Festival::where('festival_date', '>=', $today)
            ->orderBy('festival_date', 'asc')
            ->take(5)
            ->get();

        $pendingRemindersList = Reminder::with('client')
            ->where('status', 'pending')
            ->whereDate('reminder_date', '<=', $today)
            ->get();

        return view('dashboard', compact(
            'totalClients',
            'messagesSent',
            'pendingReminders',
            'failedDeliveries',
            'calendarDays',
            'upcomingFestivals',
            'pendingRemindersList',
            'today'
        ));
    }

    public function dispatchReminder($id, \App\Services\MessageDispatchService $dispatchService)
    {
        $reminder = Reminder::findOrFail($id);
        
        if ($reminder->status === Reminder::STATUS_PENDING) {
            $reminder->update(['status' => Reminder::STATUS_PROCESSING]);
            
            try {
                $dispatchService->dispatch($reminder);
                return redirect()->back()->with('success', 'Message sent successfully on configured channels!');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to send message: ' . $e->getMessage());
            }
        }
        
        return redirect()->back()->with('error', 'Reminder is already processed or sent.');
    }
}
