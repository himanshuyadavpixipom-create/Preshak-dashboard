<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Reminder;
use Illuminate\Support\Carbon;

class ReminderService
{
    /**
     * Get clients whose birthday falls within the given date window.
     * Handles leap years correctly by matching month and day.
     */
    public function getUpcomingBirthdays(Carbon $startDate, Carbon $endDate)
    {
        return $this->getUpcomingAnnualEvent('birthday', $startDate, $endDate);
    }

    /**
     * Get clients whose anniversary falls within the given date window.
     */
    public function getUpcomingAnniversaries(Carbon $startDate, Carbon $endDate)
    {
        return $this->getUpcomingAnnualEvent('anniversary_date', $startDate, $endDate);
    }

    /**
     * Get clients whose premium due date falls within the given date window.
     * Premium dates often have a year attached depending on how they are tracked,
     * but if they are recurring annually, we treat them the same.
     */
    public function getUpcomingPremiumDues(Carbon $startDate, Carbon $endDate)
    {
        return $this->getUpcomingAnnualEvent('premium_due_date', $startDate, $endDate);
    }

    /**
     * Get custom reminders from the reminders table directly.
     */
    public function getPendingCustomReminders(Carbon $startDate, Carbon $endDate)
    {
        return Reminder::where('status', 'pending')
            ->whereBetween('reminder_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->with('client')
            ->orderBy('reminder_date', 'asc')
            ->get();
    }

    /**
     * Helper to query recurring annual dates across a window (handles month/day wrapping).
     * 
     * NOTE ON LEAP YEARS: MySQL's DAY() extraction pulls exactly the day number (e.g. 29). 
     * If a person is born on Feb 29, their birthday evaluates to MONTH=2, DAY=29.
     * If we query a window from Feb 25 to March 4, MONTH=2 AND DAY>=25 captures day 29, 
     * so their reminder will still trigger in a non-leap year (even if the actual calendar 
     * date skips from Feb 28 to March 1).
     */
    protected function getUpcomingAnnualEvent(string $column, Carbon $startDate, Carbon $endDate)
    {
        $startMonth = $startDate->month;
        $startDay = $startDate->day;
        
        $endMonth = $endDate->month;
        $endDay = $endDate->day;

        $query = Client::whereNotNull($column);

        if ($startMonth === $endMonth) {
            // Same month: just check if day is between start and end
            $query->whereRaw("MONTH({$column}) = ? AND DAY({$column}) BETWEEN ? AND ?", [
                $startMonth, $startDay, $endDay
            ]);
        } else {
            // Spans across months (e.g., Dec to Jan, or Jan to Feb)
            // It's either (month = startMonth AND day >= startDay) OR (month = endMonth AND day <= endDay)
            // Or months strictly between startMonth and endMonth.
            $query->where(function ($q) use ($column, $startMonth, $startDay, $endMonth, $endDay) {
                // If it doesn't wrap the new year
                if ($startMonth < $endMonth) {
                    $q->where(function ($sub) use ($column, $startMonth, $startDay) {
                        $sub->whereRaw("MONTH({$column}) = ?", [$startMonth])
                            ->whereRaw("DAY({$column}) >= ?", [$startDay]);
                    })
                    ->orWhere(function ($sub) use ($column, $endMonth, $endDay) {
                        $sub->whereRaw("MONTH({$column}) = ?", [$endMonth])
                            ->whereRaw("DAY({$column}) <= ?", [$endDay]);
                    });
                    
                    // Add strictly between months if difference is > 1
                    if ($endMonth - $startMonth > 1) {
                        $q->orWhereRaw("MONTH({$column}) > ? AND MONTH({$column}) < ?", [$startMonth, $endMonth]);
                    }
                } else {
                    // It wraps around the new year (Dec -> Jan)
                    $q->where(function ($sub) use ($column, $startMonth, $startDay) {
                        $sub->whereRaw("MONTH({$column}) = ?", [$startMonth])
                            ->whereRaw("DAY({$column}) >= ?", [$startDay]);
                    })
                    ->orWhere(function ($sub) use ($column, $endMonth, $endDay) {
                        $sub->whereRaw("MONTH({$column}) = ?", [$endMonth])
                            ->whereRaw("DAY({$column}) <= ?", [$endDay]);
                    })
                    ->orWhereRaw("MONTH({$column}) > ?", [$startMonth])
                    ->orWhereRaw("MONTH({$column}) < ?", [$endMonth]);
                }
            });
        }

        return $query->orderByRaw("MONTH({$column}), DAY({$column})")->get();
    }
}
