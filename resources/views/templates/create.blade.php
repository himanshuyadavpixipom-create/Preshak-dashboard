<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 dark:text-white leading-tight tracking-tight">
                    {{ __('Create Message Template') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Design a new automated message</p>
            </div>
            <a href="{{ route('templates.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-colors">
                &larr; Back to Templates
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <!-- Helper Sidebar -->
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-bold leading-6 text-slate-900 dark:text-white">Template Details</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Define the channel and content for this message template.
                        </p>

                        <div class="mt-6 bg-slate-50 dark:bg-slate-800 p-5 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-soft">
                            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Available Placeholders
                            </h4>
                            <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-3">
                                @foreach($placeholders as $tag => $description)
                                    <li class="flex flex-col">
                                        <code class="font-mono text-xs font-bold text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20 px-2 py-1 rounded w-fit mb-1">{{ $tag }}</code>
                                        <span class="text-xs">{{ $description }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Form Area -->
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <form action="{{ route('templates.store') }}" method="POST">
                        @csrf
                        <div class="shadow-soft sm:rounded-2xl sm:overflow-hidden border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 transition-colors">
                            <div class="px-4 py-5 space-y-6 sm:p-8">
                                
                                <!-- Basic Info -->
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-4">
                                        <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Template Name</label>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full shadow-sm sm:text-sm rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors" required>
                                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="reminder_type" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Reminder Type</label>
                                        <select id="reminder_type" name="reminder_type" class="mt-1 block w-full py-2 px-3 shadow-sm sm:text-sm rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors" required>
                                            <option value="birthday" {{ old('reminder_type') == 'birthday' ? 'selected' : '' }}>Birthday</option>
                                            <option value="anniversary" {{ old('reminder_type') == 'anniversary' ? 'selected' : '' }}>Anniversary</option>
                                            <option value="premium_due" {{ old('reminder_type') == 'premium_due' ? 'selected' : '' }}>Premium Due</option>
                                            <option value="custom" {{ old('reminder_type') == 'custom' ? 'selected' : '' }}>Custom</option>
                                        </select>
                                        @error('reminder_type') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="channel" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Channel</label>
                                        <select id="channel" name="channel" class="mt-1 block w-full py-2 px-3 shadow-sm sm:text-sm rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors" required>
                                            <option value="whatsapp" {{ old('channel') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                            <option value="sms" {{ old('channel') == 'sms' ? 'selected' : '' }}>SMS</option>
                                            <option value="email" {{ old('channel') == 'email' ? 'selected' : '' }}>Email</option>
                                        </select>
                                        @error('channel') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Content -->
                                <div>
                                    <label for="subject" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Subject (Email Only)</label>
                                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="mt-1 block w-full shadow-sm sm:text-sm rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors" placeholder="e.g. Happy Birthday @{{client_name}}!">
                                    @error('subject') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="body" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Message Body</label>
                                    <div class="mt-1">
                                        <textarea id="body" name="body" rows="6" class="mt-1 block w-full shadow-sm sm:text-sm rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors" placeholder="Write your message here..." required>{{ old('body') }}</textarea>
                                    </div>
                                    @error('body') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Toggles -->
                                <div class="flex flex-col space-y-4 pt-4 border-t border-slate-100 dark:border-slate-700">
                                    <label class="flex items-center group cursor-pointer w-fit">
                                        <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="form-checkbox w-5 h-5 text-primary-600 border-slate-300 dark:border-slate-600 rounded focus:ring-primary-500 dark:bg-slate-900 transition-colors cursor-pointer">
                                        <span class="ml-3 text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">Active Template</span>
                                    </label>

                                    <label class="flex items-center group cursor-pointer w-fit">
                                        <input id="is_default" name="is_default" type="checkbox" value="1" {{ old('is_default') ? 'checked' : '' }} class="form-checkbox w-5 h-5 text-primary-600 border-slate-300 dark:border-slate-600 rounded focus:ring-primary-500 dark:bg-slate-900 transition-colors cursor-pointer">
                                        <span class="ml-3 text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">Set as Default for this Type/Channel</span>
                                    </label>
                                </div>
                            </div>
                            <div class="px-4 py-4 bg-slate-50 dark:bg-slate-800/50 flex justify-end gap-3 sm:px-6 border-t border-slate-100 dark:border-slate-700">
                                <a href="{{ route('templates.index') }}" class="inline-flex justify-center items-center px-4 py-2 border border-slate-300 dark:border-slate-600 shadow-sm text-sm font-medium rounded-lg text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all hover:-translate-y-0.5">
                                    Save Template
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
