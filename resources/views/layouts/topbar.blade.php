<header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200/60 dark:border-slate-800/80 sticky top-0 z-30 transition-colors duration-200">
    <div class="flex items-center justify-between px-6 lg:px-10 h-20">
        
        <!-- Left Side: Mobile Menu Toggle & Title -->
        <div class="flex items-center gap-4">
            <!-- Mobile Menu Toggle -->
            <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 focus:outline-none p-1 -ml-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Page Title Area -->
            <div class="pl-1 lg:pl-0">
                <h1 class="text-2xl font-heading font-semibold text-slate-900 dark:text-white tracking-tight leading-none">
                    {{ $title ?? 'Dashboard' }}
                </h1>
                @if(isset($subtitle))
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 font-medium">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Right Side: User Dropdown & Theme Toggle -->
        <div class="flex items-center gap-4">
            
            <!-- Theme Toggle -->
            <button @click="updateTheme(theme === 'dark' ? 'light' : 'dark')" class="p-2 rounded-full text-slate-400 hover:text-accent-600 hover:bg-accent-50 dark:hover:bg-slate-800 dark:hover:text-accent-400 transition-colors duration-200 focus:outline-none">
                <!-- Sun Icon for Light Mode -->
                <svg x-show="theme === 'dark'" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <!-- Moon Icon for Dark Mode -->
                <svg x-show="theme !== 'dark'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            </button>

            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center gap-2 pl-3 pr-2 py-2 text-sm font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/60 rounded-full hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600 focus:outline-none focus:ring-2 focus:ring-accent-500/30 transition-all duration-200 shadow-sm">
                        <div class="hidden sm:block pl-1">{{ Auth::user()->name }}</div>
                        <div class="sm:hidden pl-1">Menu</div>

                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="block px-4 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider sm:hidden">
                        {{ Auth::user()->name }}
                    </div>
                    <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</header>
