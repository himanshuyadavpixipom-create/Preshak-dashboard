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
        
        <!-- Theme Initialization -->
        @if($theme === 'system')
        <script>
            try {
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        </script>
        @endif
        
        <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/tooltip@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans text-slate-800 dark:text-slate-200 antialiased bg-slate-50 dark:bg-slate-900 transition-colors duration-200 selection:bg-accent-500 selection:text-white">
        <div x-data="{ 
                 sidebarOpen: false, 
                 sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
                 theme: '{{ $theme }}',
                 toggleSidebar() {
                     this.sidebarCollapsed = !this.sidebarCollapsed;
                     localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
                 },
                 updateTheme(newTheme) {
                     this.theme = newTheme;
                     if (newTheme === 'dark' || (newTheme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                         document.documentElement.classList.add('dark');
                     } else {
                         document.documentElement.classList.remove('dark');
                     }
                     fetch('{{ route('theme.update') }}', {
                         method: 'POST',
                         headers: {
                             'Content-Type': 'application/json',
                             'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                         },
                         body: JSON.stringify({ theme: newTheme })
                     });
                 }
             }" 
             @keydown.escape.window="sidebarOpen = false" class="min-h-screen flex">
            
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-h-screen min-w-0 bg-slate-50 dark:bg-slate-900 transition-colors duration-200">
                <!-- Topbar -->
                @include('layouts.topbar', ['title' => $title ?? null, 'subtitle' => $subtitle ?? null])

                <!-- Page Content -->
                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
