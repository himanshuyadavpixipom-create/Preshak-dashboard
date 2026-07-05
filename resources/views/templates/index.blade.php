<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 dark:text-white leading-tight tracking-tight">
                    {{ __('Message Templates') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Manage your automated messaging content</p>
            </div>
            <a href="{{ route('templates.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Template
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl flex items-center shadow-sm" role="alert">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="block sm:inline font-medium text-sm">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl flex items-center shadow-sm" role="alert">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    <span class="block sm:inline font-medium text-sm">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-soft border border-slate-100 dark:border-slate-700 overflow-hidden">
                @if($templates->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 px-4 text-center">
                        <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mb-6 border border-slate-100 dark:border-slate-700 shadow-sm">
                            <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">No templates found</h3>
                        <p class="text-slate-500 dark:text-slate-400 max-w-sm mx-auto mb-6">Create your first message template to automate SMS, WhatsApp, and email reminders for your clients.</p>
                        <a href="{{ route('templates.create') }}" class="inline-flex items-center px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-lg shadow-sm text-sm font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                            Create First Template
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700/50">
                            <thead class="bg-slate-50/50 dark:bg-slate-800/50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Name & Status</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Channel</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Event Type</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-100 dark:divide-slate-700/50">
                                @foreach ($templates as $template)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors duration-150 group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center border {{ $template->is_active ? 'bg-primary-50 dark:bg-primary-500/10 border-primary-100 dark:border-primary-500/20 text-primary-600 dark:text-primary-400' : 'bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-400' }}">
                                                    <span class="font-bold text-sm">{{ substr($template->name, 0, 1) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-slate-900 dark:text-white">
                                                        {{ $template->name }}
                                                        @if($template->is_default)
                                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-primary-100 text-primary-800 dark:bg-primary-900/50 dark:text-primary-300 uppercase tracking-wider">
                                                                Default
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                                        {{ $template->is_active ? 'Active' : 'Inactive' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600 capitalize">
                                                @if($template->channel == 'whatsapp')
                                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0C5.385 0 0 5.388 0 12.038c0 2.128.553 4.205 1.603 6.035L.15 23.4l5.467-1.433c1.763.973 3.754 1.488 5.797 1.488 6.645 0 12.031-5.388 12.031-12.038C23.445 5.388 18.06 0 12.031 0zM17.47 16.59c-.233.655-1.344 1.258-1.854 1.342-.51.084-1.164.218-3.666-.816-3.033-1.253-4.992-4.34-5.143-4.542-.15-.202-1.229-1.635-1.229-3.118s.772-2.22 1.042-2.523c.27-.302.585-.378.78-.378.195 0 .39 0 .555.008.18.008.42-.067.645.47.24.57 1.155 2.81 1.26 3.03.105.22.18.47.03.77-.15.302-.225.488-.45.74-.225.253-.465.55-.675.755-.225.22-.465.455-.21.892.255.437 1.14 1.88 2.45 3.045 1.69 1.503 3.12 1.968 3.555 2.17.435.203.69.17.945-.118.255-.287 1.095-1.272 1.395-1.71.3-.437.6-.362 1.005-.21 4.05 1.503 1.95 2.41 1.95 2.41z"/></svg>
                                                @endif
                                                {{ $template->channel }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300 capitalize font-medium">
                                            {{ str_replace('_', ' ', $template->reminder_type) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end items-center space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <a href="{{ route('templates.preview', $template) }}" class="text-slate-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors" title="Preview">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </a>
                                                <a href="{{ route('templates.edit', $template) }}" class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </a>
                                                <form action="{{ route('templates.destroy', $template) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this template?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-slate-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Delete">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
