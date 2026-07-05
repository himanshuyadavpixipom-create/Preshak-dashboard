<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <div class="text-center mb-8">
        <h2 class="text-2xl font-heading font-bold text-slate-900 dark:text-white">Welcome back</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5 font-medium">Please enter your details to sign in.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-slate-700 dark:text-slate-300 font-semibold mb-1" />
            <x-text-input id="email" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-slate-900 dark:text-white focus:ring-accent-500 focus:border-accent-500 transition-all shadow-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter your email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1">
                <x-input-label for="password" :value="__('Password')" class="text-slate-700 dark:text-slate-300 font-semibold mb-0" />
                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-accent-600 dark:text-accent-400 hover:text-accent-700 dark:hover:text-accent-300 rounded-md focus:outline-none focus:underline transition-colors" href="{{ route('password.request') }}">
                        {{ __('Forgot?') }}
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-slate-900 dark:text-white focus:ring-accent-500 focus:border-accent-500 transition-all shadow-sm"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-2">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 dark:border-slate-600 text-accent-600 shadow-sm focus:ring-accent-500 dark:focus:ring-accent-500 dark:bg-slate-900 transition-all cursor-pointer" name="remember">
                <span class="ms-2 text-sm font-medium text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-200 transition-colors">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="pt-2">
            <button class="w-full justify-center py-3 px-4 bg-accent-600 hover:bg-accent-700 dark:bg-accent-500 dark:hover:bg-accent-600 text-white font-bold rounded-xl shadow-sm hover:shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500 dark:focus:ring-offset-slate-800">
                {{ __('Sign in') }}
            </button>
        </div>
    </form>
</x-guest-layout>
