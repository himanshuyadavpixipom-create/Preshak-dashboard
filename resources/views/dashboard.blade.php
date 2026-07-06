<x-app-layout>
    <x-slot name="title">Overview</x-slot>
    <x-slot name="subtitle">Welcome back to Preshak CRM</x-slot>

    <div class="space-y-8">
        
        <!-- Top Section: Calendar -->
        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-heading font-bold text-slate-900 dark:text-white">{{ $today->format('F Y') }}</h2>
                <div class="flex items-center gap-4 text-xs font-medium text-slate-500 dark:text-slate-400">
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-accent-500"></span> Reminders</div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-violet-500"></span> Festivals</div>
                </div>
            </div>
            
            <x-calendar :days="$calendarDays" />
        </section>

        <!-- Middle Section: KPIs -->
        <section>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Clients -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 flex flex-col justify-between shadow-soft border border-slate-200/60 dark:border-slate-700/60 transition-transform duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Clients</h3>
                        <div class="p-2 bg-slate-50 dark:bg-slate-800/80 rounded-xl">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4"><span class="text-3xl font-heading font-extrabold text-slate-900 dark:text-white">{{ $totalClients }}</span></div>
                </div>

                <!-- Messages Sent -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 flex flex-col justify-between shadow-soft border border-slate-200/60 dark:border-slate-700/60 transition-transform duration-300 hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-accent-500/10 rounded-full blur-2xl"></div>
                    <div class="flex items-center justify-between relative z-10">
                        <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">Messages Sent</h3>
                        <div class="p-2 bg-accent-50 dark:bg-accent-500/10 rounded-xl">
                            <svg class="w-5 h-5 text-accent-600 dark:text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 relative z-10"><span class="text-3xl font-heading font-extrabold text-slate-900 dark:text-white">{{ $messagesSent }}</span></div>
                </div>

                <!-- Pending Reminders -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 flex flex-col justify-between shadow-soft border border-slate-200/60 dark:border-slate-700/60 transition-transform duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">Pending Reminders</h3>
                        <div class="p-2 bg-amber-50 dark:bg-amber-500/10 rounded-xl">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4"><span class="text-3xl font-heading font-extrabold text-slate-900 dark:text-white">{{ $pendingReminders }}</span></div>
                </div>

                <!-- Delivery Failed -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 flex flex-col justify-between shadow-soft border border-slate-200/60 dark:border-slate-700/60 transition-transform duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">Failed Deliveries</h3>
                        <div class="p-2 bg-red-50 dark:bg-red-500/10 rounded-xl">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4"><span class="text-3xl font-heading font-extrabold text-slate-900 dark:text-white">{{ $failedDeliveries }}</span></div>
                </div>
            </div>
        </section>

        <!-- Bottom Section: Recent Activity & Festivals -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Today's Pending Reminders -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-soft border border-slate-200/60 dark:border-slate-700/60 overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700/60 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/30">
                    <h3 class="text-lg font-heading font-bold text-slate-900 dark:text-white">Action Required</h3>
                    <form method="POST" action="{{ route('reminders.scan') }}" class="m-0">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors flex items-center gap-1" title="Scan for new reminders">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Refresh
                        </button>
                    </form>
                </div>
                <div class="p-0 flex-1">
                    @if(isset($pendingRemindersList) && $pendingRemindersList->count() > 0)
                        <form method="POST" action="{{ route('reminders.dispatch.bulk') }}" id="bulk-dispatch-form">
                            @csrf
                            <div class="px-6 py-3 bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-700/50 flex justify-between items-center">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" id="select-all-reminders" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500 cursor-pointer" onclick="document.querySelectorAll('.reminder-checkbox').forEach(cb => cb.checked = this.checked)">
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Select All</span>
                                </label>
                                <button type="submit" class="px-3 py-1.5 bg-accent-600 hover:bg-accent-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm flex items-center gap-1.5" onclick="return confirm('Send messages for all selected reminders?');">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                    Send Selected
                                </button>
                            </div>
                            <ul class="divide-y divide-slate-100 dark:divide-slate-700/50">
                                @foreach($pendingRemindersList as $rem)
                                    <li class="px-6 py-4 hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <input type="checkbox" name="reminder_ids[]" value="{{ $rem->id }}" class="reminder-checkbox rounded border-slate-300 text-primary-600 focus:ring-primary-500 cursor-pointer">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $rem->client->name ?? 'Unknown' }}</p>
                                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                                    <span class="capitalize font-medium text-slate-700 dark:text-slate-300">{{ str_replace('_', ' ', $rem->type) }}</span> 
                                                    &bull; {{ \Carbon\Carbon::parse($rem->event_date)->format('d M, Y') }}
                                                    @if($rem->type === 'premium_due' && $rem->client)
                                                        <br><span class="text-slate-400 dark:text-slate-500 text-[11px]">Policy: {{ $rem->client->policy_number ?? 'N/A' }} ({{ $rem->client->policy_name ?? 'N/A' }})</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" onclick="document.getElementById('dispatch-form-{{ $rem->id }}').submit();" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-lg transition-colors shadow-sm">
                                            Send Now
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </form>
                        @foreach($pendingRemindersList as $rem)
                            <form method="POST" action="{{ route('reminders.dispatch', $rem->id) }}" id="dispatch-form-{{ $rem->id }}" class="hidden">@csrf</form>
                        @endforeach
                    @else
                        <div class="flex flex-col items-center justify-center py-12 text-center px-4">
                            <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mb-4 border border-slate-100 dark:border-slate-700/50">
                                <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">All caught up!</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">No pending reminders for today.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Dispatches -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-soft border border-slate-200/60 dark:border-slate-700/60 overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700/60 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/30">
                    <h3 class="text-lg font-heading font-bold text-slate-900 dark:text-white">Recent Dispatches</h3>
                    <a href="{{ route('logs.index') }}" class="text-sm font-medium text-accent-600 dark:text-accent-400 hover:text-accent-700 dark:hover:text-accent-300 transition-colors">View all &rarr;</a>
                </div>
                <div class="p-0 flex-1">
                    @php
                        $recentLogs = \App\Models\DeliveryLog::with('reminder.client')->latest()->take(5)->get();
                    @endphp
                    @if($recentLogs->count() > 0)
                        <ul class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @foreach($recentLogs as $log)
                                <li class="px-6 py-4 hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors duration-150 flex items-center justify-between group">
                                    <div class="flex items-center gap-4">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center {{ $log->status == 'sent' ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : ($log->status == 'failed' ? 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400' : 'bg-slate-50 dark:bg-slate-800 text-slate-500 border border-slate-200 dark:border-slate-700') }}">
                                            @if($log->channel == 'whatsapp')
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0C5.385 0 0 5.388 0 12.038c0 2.128.553 4.205 1.603 6.035L.15 23.4l5.467-1.433c1.763.973 3.754 1.488 5.797 1.488 6.645 0 12.031-5.388 12.031-12.038C23.445 5.388 18.06 0 12.031 0zM17.47 16.59c-.233.655-1.344 1.258-1.854 1.342-.51.084-1.164.218-3.666-.816-3.033-1.253-4.992-4.34-5.143-4.542-.15-.202-1.229-1.635-1.229-3.118s.772-2.22 1.042-2.523c.27-.302.585-.378.78-.378.195 0 .39 0 .555.008.18.008.42-.067.645.47.24.57 1.155 2.81 1.26 3.03.105.22.18.47.03.77-.15.302-.225.488-.45.74-.225.253-.465.55-.675.755-.225.22-.465.455-.21.892.255.437 1.14 1.88 2.45 3.045 1.69 1.503 3.12 1.968 3.555 2.17.435.203.69.17.945-.118.255-.287 1.095-1.272 1.395-1.71.3-.437.6-.362 1.005-.21 4.05 1.503 1.95 2.41 1.95 2.41z"/></svg>
                                            @elseif($log->channel == 'email')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                                {{ $log->reminder ? ($log->reminder->client->name ?? 'Unknown Client') : 'Unknown' }}
                                            </p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 flex items-center gap-1.5">
                                                <span class="capitalize">{{ $log->channel }}</span> &bull; {{ $log->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('logs.show', $log) }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Details</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mb-4 border border-slate-100 dark:border-slate-700/50">
                                <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">No deliveries yet</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Records will appear here once reminders are sent.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Festivals -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-soft border border-slate-200/60 dark:border-slate-700/60 overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700/60 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/30">
                    <h3 class="text-lg font-heading font-bold text-slate-900 dark:text-white">Upcoming Festivals</h3>
                    <a href="{{ route('festivals.index') }}" class="text-sm font-medium text-accent-600 dark:text-accent-400 hover:text-accent-700 dark:hover:text-accent-300 transition-colors">Manage</a>
                </div>
                <div class="p-0 flex-1">
                    @if(isset($upcomingFestivals) && $upcomingFestivals->count() > 0)
                        <ul class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @foreach($upcomingFestivals as $festival)
                                <li class="px-6 py-4 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $festival->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $festival->category ?? 'Event' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-violet-600 dark:text-violet-400">{{ $festival->festival_date->format('M j') }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $festival->festival_date->diffForHumans() }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 text-center px-4">
                            <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mb-4 border border-slate-100 dark:border-slate-700/50">
                                <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">No upcoming festivals</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Add festivals to keep track of important dates.</p>
                            <a href="{{ route('festivals.create') }}" class="mt-4 px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-lg transition-colors">Add Festival</a>
                        </div>
                    @endif
                </div>
            </div>

        </section>
    </div>
</x-app-layout>
