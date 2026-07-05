<x-app-layout>
    <x-slot name="title">Festivals</x-slot>
    <x-slot name="subtitle">Manage upcoming festivals and events.</x-slot>

    <div class="mb-6 flex justify-end">
        <a href="{{ route('festivals.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-accent-600 hover:bg-accent-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500 transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Festival
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-soft border border-slate-200/60 dark:border-slate-700/60 overflow-hidden">
        @if($festivals->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-200/60 dark:border-slate-700/60">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Festival Name</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @foreach($festivals as $festival)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors group">
                                <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-white">
                                    {{ $festival->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                    {{ $festival->festival_date->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                        {{ $festival->category ?? 'Event' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('festivals.edit', $festival) }}" class="p-2 text-slate-400 hover:text-accent-600 hover:bg-accent-50 dark:hover:bg-slate-700 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <form action="{{ route('festivals.destroy', $festival) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this festival?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($festivals->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/60">
                    {{ $festivals->links() }}
                </div>
            @endif
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center px-4">
                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800/80 rounded-full flex items-center justify-center mb-5 border border-slate-100 dark:border-slate-700/50">
                    <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-lg font-heading font-bold text-slate-900 dark:text-white">No festivals found</h3>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 max-w-sm">Keep track of important events to populate your calendar.</p>
                <a href="{{ route('festivals.create') }}" class="mt-6 px-5 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-semibold rounded-xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-colors shadow-sm">
                    Add your first festival
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
