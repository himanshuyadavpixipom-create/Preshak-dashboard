<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 dark:text-white leading-tight tracking-tight">
                    {{ __('Integration Center') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Configure external services and APIs</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('integrations.update') }}" method="POST">
                @csrf
                <div class="space-y-6">

                    <!-- Email Integration Settings -->
                    <div class="shadow-soft sm:rounded-2xl sm:overflow-hidden border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 transition-colors px-4 py-5 sm:p-8">
                        <div class="md:grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <h3 class="text-lg font-bold leading-6 text-slate-900 dark:text-slate-100">Email (SMTP) Integration</h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                    Configure your outbound email server. Fallback secrets in .env will be used if left blank.
                                </p>
                            </div>
                            <div class="mt-5 md:mt-0 md:col-span-2">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-3">
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Mailer</label>
                                        <input type="text" name="mail_mailer" value="{{ $settings['mail_mailer'] ?? config('mail.default') }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded-md">
                                    </div>
                                    <div class="col-span-6 sm:col-span-3">
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Host</label>
                                        <input type="text" name="mail_host" value="{{ $settings['mail_host'] ?? config('mail.mailers.smtp.host') }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded-md">
                                    </div>
                                    <div class="col-span-6 sm:col-span-2">
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Port</label>
                                        <input type="text" name="mail_port" value="{{ $settings['mail_port'] ?? config('mail.mailers.smtp.port') }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded-md">
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Username</label>
                                        <input type="text" name="mail_username" value="{{ $settings['mail_username'] ?? config('mail.mailers.smtp.username') }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded-md">
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Password</label>
                                        <input type="password" name="mail_password" value="{{ $settings['mail_password'] ?? '' }}" placeholder="********" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded-md">
                                    </div>
                                    <div class="col-span-6 sm:col-span-2">
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Encryption</label>
                                        <input type="text" name="mail_encryption" value="{{ $settings['mail_encryption'] ?? config('mail.mailers.smtp.encryption') }}" placeholder="tls/ssl" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded-md">
                                    </div>
                                    <div class="col-span-6 sm:col-span-3">
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">From Address</label>
                                        <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? config('mail.from.address') }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded-md">
                                    </div>
                                    <div class="col-span-6 sm:col-span-3">
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">From Name</label>
                                        <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? config('mail.from.name') }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded-md">
                                    </div>

                                    <div class="col-span-6">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="email_simulate" name="email_simulate" type="checkbox" value="1" {{ ($settings['email_simulate'] ?? '0') == '1' ? 'checked' : '' }} class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="email_simulate" class="font-medium text-slate-700 dark:text-slate-300">Simulation Mode</label>
                                                <p class="text-slate-500">If checked, emails will be simulated and recorded as success without actually sending.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SMS Integration Settings -->
                    <div class="shadow-soft sm:rounded-2xl sm:overflow-hidden border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 transition-colors px-4 py-5 sm:p-8">
                        <div class="md:grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <h3 class="text-lg font-bold leading-6 text-slate-900 dark:text-slate-100">SMS Integration</h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                    Configure your Twilio account for SMS sending.
                                </p>
                            </div>
                            <div class="mt-5 md:mt-0 md:col-span-2">
                                <div class="grid grid-cols-6 gap-6">
                                    
                                    <div class="col-span-6">
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Twilio Account SID</label>
                                        <input type="text" name="twilio_sid" value="{{ $settings['twilio_sid'] ?? '' }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded-md">
                                    </div>
                                    <div class="col-span-6">
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Twilio Auth Token</label>
                                        <input type="password" name="twilio_auth_token" value="{{ $settings['twilio_auth_token'] ?? '' }}" placeholder="********" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded-md">
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">From / Sender ID</label>
                                        <input type="text" name="twilio_from" value="{{ $settings['twilio_from'] ?? '' }}" placeholder="+1234567890" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded-md">
                                    </div>

                                    <div class="col-span-6">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="sms_simulate" name="sms_simulate" type="checkbox" value="1" {{ ($settings['sms_simulate'] ?? '1') == '1' ? 'checked' : '' }} class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="sms_simulate" class="font-medium text-slate-700 dark:text-slate-300">Simulation Mode</label>
                                                <p class="text-slate-500">If checked, SMS sending will be simulated. Strongly recommended during testing.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-6">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="sms_active" name="sms_active" type="checkbox" value="1" {{ ($settings['sms_active'] ?? '1') == '1' ? 'checked' : '' }} class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="sms_active" class="font-medium text-slate-700 dark:text-slate-300">Integration Active</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- WhatsApp Integration Settings -->
                    <div class="shadow-soft sm:rounded-2xl sm:overflow-hidden border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 transition-colors px-4 py-5 sm:p-8">
                        <div class="md:grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <h3 class="text-lg font-bold leading-6 text-slate-900 dark:text-slate-100">WhatsApp Integration</h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                    Configure your Twilio WhatsApp Sender (Uses Twilio SID and Auth Token from SMS Settings).
                                </p>
                            </div>
                            <div class="mt-5 md:mt-0 md:col-span-2">
                                <div class="grid grid-cols-6 gap-6">
                                    
                                    <div class="col-span-6">
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Twilio WhatsApp From Number</label>
                                        <input type="text" name="twilio_whatsapp_from" value="{{ $settings['twilio_whatsapp_from'] ?? '' }}" placeholder="+14155238886" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded-md">
                                    </div>

                                    <div class="col-span-6">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="whatsapp_simulate" name="whatsapp_simulate" type="checkbox" value="1" {{ ($settings['whatsapp_simulate'] ?? '1') == '1' ? 'checked' : '' }} class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="whatsapp_simulate" class="font-medium text-slate-700 dark:text-slate-300">Simulation Mode</label>
                                                <p class="text-slate-500">If checked, WhatsApp messages will be simulated.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-6">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="whatsapp_active" name="whatsapp_active" type="checkbox" value="1" {{ ($settings['whatsapp_active'] ?? '1') == '1' ? 'checked' : '' }} class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="whatsapp_active" class="font-medium text-slate-700 dark:text-slate-300">Integration Active</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="inline-flex justify-center items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all hover:-translate-y-0.5">
                            Save Integration Settings
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
