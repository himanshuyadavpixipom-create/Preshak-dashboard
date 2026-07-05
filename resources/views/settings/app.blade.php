<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 dark:text-white leading-tight tracking-tight">
                    {{ __('App Settings') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Manage global web application preferences</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="shadow-soft sm:rounded-2xl sm:overflow-hidden border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 transition-colors">
                <div class="px-4 py-5 space-y-6 sm:p-8">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <h3 class="text-lg font-bold leading-6 text-slate-900 dark:text-white">Preferences</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                Configure your application preferences like theme and timezone.
                            </p>
                        </div>
                        <div class="mt-5 md:mt-0 md:col-span-2 space-y-6">
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Theme Preference</label>
                                <select x-model="theme" @change="updateTheme($el.value)" class="mt-1 block w-full py-2 px-3 shadow-sm sm:text-sm rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors">
                                    <option value="system">System Default</option>
                                    <option value="light">Light Mode</option>
                                    <option value="dark">Dark Mode</option>
                                </select>
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Updates immediately and saves securely in your active session.</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
