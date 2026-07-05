<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Preview Template: ') }} {{ $template->name }}
            </h2>
            <a href="{{ route('templates.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">&larr; Back to Templates</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                            Render Context
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                            Currently previewing using client: <strong>{{ $client->name }}</strong>
                            @if($reminder)
                                <span class="text-xs text-gray-400 ml-2">(Simulating via Reminder ID #{{ $reminder->id }})</span>
                            @else
                                <span class="text-xs text-gray-400 ml-2">(Simulating via Random Client Context)</span>
                            @endif
                        </p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 capitalize">
                        {{ $template->channel }} Message
                    </span>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="bg-gray-100 dark:bg-gray-900 p-6 rounded-lg shadow-inner flex justify-center">
                
                @if($template->channel === 'whatsapp' || $template->channel === 'sms')
                    <!-- Mobile Device Mockup -->
                    <div class="w-full max-w-sm bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden border-4 border-gray-300 dark:border-gray-700 relative">
                        <div class="bg-gray-200 dark:bg-gray-700 py-3 px-4 text-center border-b border-gray-300 dark:border-gray-600">
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $client->name }}</span>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-[#0b141a] min-h-[300px] flex flex-col justify-end relative">
                            <!-- WhatsApp chat background pattern simulation -->
                            @if($template->channel === 'whatsapp')
                                <div class="absolute inset-0 opacity-5 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+CjxwYXRoIGQ9Ik0wIDBoNDB2NDBIMHoiIGZpbGw9Im5vbmUiLz4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMiIgZmlsbD0iIzAwMCIvPgo8L3N2Zz4=')]"></div>
                            @endif
                            
                            <div class="bg-green-100 dark:bg-[#005c4b] text-gray-800 dark:text-gray-100 p-3 rounded-lg rounded-tr-none shadow self-end max-w-[85%] relative z-10">
                                <p class="text-sm whitespace-pre-wrap font-sans">{{ $rendered['body'] }}</p>
                                <div class="text-[10px] text-gray-500 dark:text-gray-400 text-right mt-1">{{ now()->format('g:i A') }}</div>
                            </div>
                        </div>
                    </div>
                @elseif($template->channel === 'email')
                    <!-- Email Desktop Mockup -->
                    <div class="w-full bg-white dark:bg-gray-800 rounded shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
                        <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex flex-col gap-2">
                            <div class="text-sm">
                                <span class="font-medium text-gray-500 dark:text-gray-400 w-16 inline-block">To:</span> 
                                <span class="text-gray-900 dark:text-gray-100">{{ $client->email ?? 'no-email@provided.com' }}</span>
                            </div>
                            <div class="text-sm">
                                <span class="font-medium text-gray-500 dark:text-gray-400 w-16 inline-block">Subject:</span> 
                                <span class="text-gray-900 dark:text-gray-100 font-semibold">{{ $rendered['subject'] ?: '(No Subject)' }}</span>
                            </div>
                        </div>
                        <div class="p-6 text-gray-800 dark:text-gray-200 text-base leading-relaxed whitespace-pre-wrap font-sans min-h-[250px]">
                            {{ $rendered['body'] }}
                        </div>
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</x-app-layout>
