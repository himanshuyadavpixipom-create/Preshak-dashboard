<x-app-layout>
    <x-slot name="title">Add Client</x-slot>
    <x-slot name="subtitle">Create a new client profile to start tracking reminders.</x-slot>

    <div class="mb-6">
        <a href="{{ route('clients.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Clients
        </a>
    </div>

    <form action="{{ route('clients.store') }}" method="POST">
        @csrf
        <div class="space-y-6 lg:space-y-8">
            
            <!-- Basic Contact Info -->
            <x-section-card title="Contact Information">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="name" value="Full Name *" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email Address" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" autocomplete="email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="phone" value="Phone Number" />
                        <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone')" autocomplete="tel" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="whatsapp_number" value="WhatsApp Number" />
                        <x-text-input id="whatsapp_number" name="whatsapp_number" type="tel" class="mt-1 block w-full" :value="old('whatsapp_number')" />
                        <x-input-error :messages="$errors->get('whatsapp_number')" class="mt-2" />
                    </div>

                    <div class="sm:col-span-2">
                        <x-input-label for="company_name" value="Company / Organization" />
                        <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" :value="old('company_name')" />
                        <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                    </div>

                    <div class="sm:col-span-2">
                        <x-input-label for="address" value="Physical Address" />
                        <textarea id="address" name="address" rows="3" class="mt-1 block w-full border-slate-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm text-slate-900 sm:text-sm transition-colors duration-200">{{ old('address') }}</textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>
                </div>
            </x-section-card>

            <!-- Important Dates -->
            <x-section-card title="Important Dates">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <x-input-label for="birthday" value="Birthday" />
                        <x-text-input id="birthday" name="birthday" type="date" class="mt-1 block w-full" :value="old('birthday')" />
                        <x-input-error :messages="$errors->get('birthday')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="anniversary_date" value="Anniversary" />
                        <x-text-input id="anniversary_date" name="anniversary_date" type="date" class="mt-1 block w-full" :value="old('anniversary_date')" />
                        <x-input-error :messages="$errors->get('anniversary_date')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="premium_due_date" value="Premium Due Date" />
                        <x-text-input id="premium_due_date" name="premium_due_date" type="date" class="mt-1 block w-full" :value="old('premium_due_date')" />
                        <x-input-error :messages="$errors->get('premium_due_date')" class="mt-2" />
                    </div>
                </div>
            </x-section-card>

            <!-- Policy & Notes -->
            <x-section-card title="Policy & Additional Details">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    <div>
                        <x-input-label for="policy_name" value="Policy Name" />
                        <x-text-input id="policy_name" name="policy_name" type="text" class="mt-1 block w-full" :value="old('policy_name')" placeholder="e.g. Health Shield Gold" />
                        <x-input-error :messages="$errors->get('policy_name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="policy_number" value="Policy Number" />
                        <x-text-input id="policy_number" name="policy_number" type="text" class="mt-1 block w-full" :value="old('policy_number')" />
                        <x-input-error :messages="$errors->get('policy_number')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <x-input-label for="notes" value="Private Notes" />
                        <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-slate-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm text-slate-900 sm:text-sm transition-colors duration-200 placeholder-slate-400" placeholder="Add any specific preferences, family details, or context...">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="status" value="Status" />
                        <select id="status" name="status" class="mt-1 block w-full border-slate-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm text-slate-900 sm:text-sm transition-colors duration-200">
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                </div>
            </x-section-card>

            <!-- Submit Action -->
            <div class="flex items-center justify-end gap-4 pb-10">
                <a href="{{ route('clients.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-slate-300 rounded-lg font-semibold text-sm text-slate-700 tracking-wide shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500/50 transition-all">
                    Cancel
                </a>
                <x-primary-button>
                    Save Client
                </x-primary-button>
            </div>

        </div>
    </form>
</x-app-layout>
