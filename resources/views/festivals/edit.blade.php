<x-app-layout>
    <x-slot name="title">Edit Festival</x-slot>
    
    <div class="max-w-2xl">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-soft border border-slate-200/60 dark:border-slate-700/60 p-6 sm:p-8">
            <form action="{{ route('festivals.update', $festival) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <x-input-label for="name" value="Festival Name" class="text-slate-700 dark:text-slate-300 font-semibold mb-1.5" />
                    <x-text-input id="name" name="name" type="text" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-slate-900 dark:text-white focus:ring-accent-500 transition-all shadow-sm" :value="old('name', $festival->name)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="festival_date" value="Date" class="text-slate-700 dark:text-slate-300 font-semibold mb-1.5" />
                    <x-text-input id="festival_date" name="festival_date" type="date" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-slate-900 dark:text-white focus:ring-accent-500 transition-all shadow-sm" :value="old('festival_date', $festival->festival_date->format('Y-m-d'))" required />
                    <x-input-error class="mt-2" :messages="$errors->get('festival_date')" />
                </div>
                
                <div>
                    <x-input-label for="category" value="Category (Optional)" class="text-slate-700 dark:text-slate-300 font-semibold mb-1.5" />
                    <x-text-input id="category" name="category" type="text" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-slate-900 dark:text-white focus:ring-accent-500 transition-all shadow-sm" :value="old('category', $festival->category)" />
                    <x-input-error class="mt-2" :messages="$errors->get('category')" />
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-700/60">
                    <a href="{{ route('festivals.index') }}" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">Cancel</a>
                    <button type="submit" class="px-5 py-2 bg-accent-600 hover:bg-accent-700 text-white font-bold rounded-xl shadow-sm transition-colors">Update Festival</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
