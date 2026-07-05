<!-- Mobile Sidebar Overlay -->
<div 
    x-show="sidebarOpen" 
    style="display: none;"
    class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="sidebarOpen = false"
></div>

<!-- Sidebar -->
<aside 
    class="fixed inset-y-0 left-0 z-50 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transform transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col shadow-sm"
    :class="{
        'w-64': !sidebarCollapsed || sidebarOpen, 
        'w-20': sidebarCollapsed && !sidebarOpen,
        'translate-x-0': sidebarOpen, 
        '-translate-x-full': !sidebarOpen
    }"
>
    <!-- Brand Area -->
    <div class="flex items-center justify-between h-20 px-4 border-b border-slate-200/60 dark:border-slate-800/80 bg-white dark:bg-slate-900">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 overflow-hidden ml-2" :class="{'mx-auto ml-0': sidebarCollapsed && !sidebarOpen}">
            <x-application-logo class="w-8 h-8 flex-shrink-0 text-accent-600 dark:text-accent-400 fill-current" />
            <span x-show="!sidebarCollapsed || sidebarOpen" class="text-xl font-heading font-bold text-slate-900 dark:text-white tracking-tight whitespace-nowrap">Preshak</span>
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Navigation Area -->
    <nav class="flex-1 px-3 py-6 space-y-1.5 overflow-y-auto overflow-x-hidden scrollbar-hide">
        
        <x-sidebar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="dashboard" label="Dashboard" />
        <x-sidebar-link href="{{ route('clients.index') }}" :active="request()->routeIs('clients.*')" icon="users" label="Clients" />
        <x-sidebar-link href="{{ route('templates.index') }}" :active="request()->routeIs('templates.*')" icon="template" label="Templates" />
        <x-sidebar-link href="{{ route('logs.index') }}" :active="request()->routeIs('logs.*')" icon="logs" label="Delivery Logs" />
        <x-sidebar-link href="{{ route('festivals.index') }}" :active="request()->routeIs('festivals.*')" icon="festivals" label="Festivals" />

        <div class="pt-6 mt-4 border-t border-slate-200/60 dark:border-slate-800/80">
            <div x-show="!sidebarCollapsed || sidebarOpen" class="px-4 pb-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">Configuration</div>
            <x-sidebar-link href="{{ route('integrations.index') }}" :active="request()->routeIs('integrations.*')" icon="integration" label="Integrations" />
            <x-sidebar-link href="{{ route('settings.index') }}" :active="request()->routeIs('settings.*')" icon="settings" label="Settings" />
        </div>
    </nav>
    
    <!-- Collapse Toggle (Desktop only) -->
    <div class="hidden lg:flex items-center justify-center p-4 border-t border-slate-200/60 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900">
        <button @click="toggleSidebar()" class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200/50 dark:hover:text-slate-300 dark:hover:bg-slate-800 transition-colors focus:outline-none">
            <svg x-show="!sidebarCollapsed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
            <svg x-show="sidebarCollapsed" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
        </button>
    </div>
</aside>
