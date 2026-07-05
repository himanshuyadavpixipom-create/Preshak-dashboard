<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Delivery Log Details') }}
            </h2>
            <a href="{{ route('logs.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">&larr; Back to Logs</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                            Log #{{ $log->id }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                            Attempted on {{ $log->created_at->format('F j, Y g:i A') }}
                        </p>
                    </div>
                    <div>
                        @if($log->status === 'sent')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Sent successfully
                            </span>
                        @elseif($log->status === 'failed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                Delivery failed
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 capitalize">
                                {{ $log->status }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <dl>
                        <div class="bg-gray-50 dark:bg-gray-900 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Recipient Client</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                @if($log->reminder && $log->reminder->client)
                                    <a href="{{ route('clients.show', $log->reminder->client) }}" class="text-blue-600 hover:underline">
                                        {{ $log->reminder->client->name }}
                                    </a>
                                @else
                                    Unknown / Deleted Client
                                @endif
                            </dd>
                        </div>
                        <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Destination</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                {{ $log->recipient }} ({{ ucfirst($log->channel) }})
                            </dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Provider</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                {{ class_basename($log->provider) }}
                                @if($log->provider_message_id)
                                    <span class="ml-2 text-xs text-gray-500">(ID: {{ $log->provider_message_id }})</span>
                                @endif
                            </dd>
                        </div>
                        @if($log->subject)
                        <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Subject</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                {{ $log->subject }}
                            </dd>
                        </div>
                        @endif
                        <div class="bg-gray-50 dark:bg-gray-900 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Message Payload</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2 whitespace-pre-wrap bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-3 rounded-md">{{ $log->body }}</dd>
                        </div>
                        
                        @if($log->status === 'failed')
                        <div class="bg-red-50 dark:bg-red-900/30 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-t border-red-200 dark:border-red-800">
                            <dt class="text-sm font-medium text-red-800 dark:text-red-200">Failure Reason</dt>
                            <dd class="mt-1 text-sm text-red-900 dark:text-red-100 sm:mt-0 sm:col-span-2 whitespace-pre-wrap font-mono text-xs">{{ $log->failure_reason }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
