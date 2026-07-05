<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 dark:text-white leading-tight tracking-tight">
                    {{ __('Delivery Logs') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Track history of dispatched messages</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filter Form -->
            <div class="bg-white dark:bg-slate-800 shadow-soft sm:rounded-2xl border border-slate-100 dark:border-slate-700 transition-colors mb-6 p-4 border border-slate-200 dark:border-slate-700">
                <form action="{{ route('logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Recipient</label>
                        <input type="text" name="recipient" value="{{ request('recipient') }}" placeholder="Phone or Email" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Channel</label>
                        <select name="channel" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            <option value="">All Channels</option>
                            <option value="whatsapp" {{ request('channel') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                            <option value="sms" {{ request('channel') == 'sms' ? 'selected' : '' }}>SMS</option>
                            <option value="email" {{ request('channel') == 'email' ? 'selected' : '' }}>Email</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Status</label>
                        <select name="status" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            <option value="">All Statuses</option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Date Range</label>
                        <div class="flex items-center space-x-2 mt-1">
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            <span class="text-slate-500">-</span>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        </div>
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="w-full bg-primary-600 border border-transparent rounded-md py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Filter
                        </button>
                        <a href="{{ route('logs.index') }}" class="w-full bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-md py-2 px-4 inline-flex justify-center text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-soft sm:rounded-2xl border border-slate-100 dark:border-slate-700 transition-colors">
                <div class="p-6 bg-white dark:bg-slate-800 ">
                    
                    @if($logs->isEmpty())
                        <x-empty-state 
                            icon="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" 
                            title="No logs found" 
                            description="No message deliveries have been attempted yet." 
                        />
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                                <thead class="bg-slate-50 dark:bg-slate-900/50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Recipient</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Channel</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Provider / Error</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                    @foreach ($logs as $log)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                                {{ $log->created_at->format('M j, Y g:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-slate-900 dark:text-white">
                                                    {{ $log->reminder ? ($log->reminder->client->name ?? 'Unknown') : 'Unknown' }}
                                                </div>
                                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                                    {{ $log->recipient }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300 capitalize">
                                                    {{ $log->channel }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($log->status === 'sent')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        Sent
                                                    </span>
                                                @elseif($log->status === 'failed')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        Failed
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 capitalize">
                                                        {{ $log->status }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                                @if($log->status === 'failed')
                                                    <span class="text-red-500" title="{{ $log->failure_reason }}">{{ Str::limit($log->failure_reason, 30) }}</span>
                                                @else
                                                    <span class="text-xs" title="{{ $log->provider_message_id }}">ID: {{ Str::limit($log->provider_message_id ?? 'N/A', 15) }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('logs.show', $log) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $logs->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
