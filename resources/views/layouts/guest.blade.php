@php
    $theme = session('theme', 'system');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $theme === 'dark' ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @if($theme === 'system')
        <script>
            try {
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        </script>
        @endif
    </head>
    <body class="font-sans text-slate-800 dark:text-slate-200 antialiased selection:bg-accent-500 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50 dark:bg-slate-900 relative overflow-hidden">
            
            <!-- Subtle background decorative blobs -->
            <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-accent-400/10 dark:bg-accent-500/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[30rem] h-[30rem] bg-indigo-400/10 dark:bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>

            <div class="z-10 relative">
                <a href="/" class="flex flex-col items-center gap-3 group">
                    <x-application-logo class="w-14 h-14 fill-current text-accent-600 dark:text-accent-400 transition-transform duration-300 group-hover:scale-105" />
                    <span class="text-3xl font-heading font-extrabold tracking-tight text-slate-900 dark:text-white">Preshak</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-10 px-8 py-10 bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-soft dark:shadow-glow-dark border border-slate-200/60 dark:border-slate-700/60 rounded-3xl z-10 relative">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
