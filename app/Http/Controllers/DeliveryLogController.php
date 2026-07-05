<?php

namespace App\Http\Controllers;

use App\Models\DeliveryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DeliveryLogController extends Controller
{
    public function index(Request $request)
    {
        $query = DeliveryLog::with('reminder.client')->latest();

        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('recipient')) {
            $query->where('recipient', 'like', '%' . $request->recipient . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->date_from));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->date_to));
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('logs.index', compact('logs'));
    }

    public function show(DeliveryLog $log)
    {
        $log->load('reminder.client');
        return view('logs.show', compact('log'));
    }
}
