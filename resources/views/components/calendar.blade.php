@props(['days'])

<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-soft border border-slate-200/60 dark:border-slate-700/60 overflow-hidden" 
     x-data="{ 
         selectedDay: null,
         openMobileSheet(day) {
             if (window.innerWidth < 1024 && day.events.length > 0) {
                 this.selectedDay = day;
             }
         }
     }">
     
    <!-- Weekdays Header -->
    <div class="grid grid-cols-7 border-b border-slate-200/60 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-800/30">
        @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $dayName)
            <div class="py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                <span class="hidden sm:inline">{{ $dayName }}</span>
                <span class="sm:hidden">{{ substr($dayName, 0, 1) }}</span>
            </div>
        @endforeach
    </div>

    <!-- Calendar Grid -->
    <div class="grid grid-cols-7 bg-slate-200/60 dark:bg-slate-700/60 gap-px">
        @foreach($days as $day)
            <div @click="openMobileSheet({{ json_encode($day) }})" 
                 class="min-h-[100px] bg-white dark:bg-slate-800 p-2 sm:p-3 relative group transition-colors lg:cursor-default cursor-pointer {{ !$day['isCurrentMonth'] ? 'bg-slate-50/50 dark:bg-slate-800/50 text-slate-400 dark:text-slate-600' : 'text-slate-900 dark:text-slate-200' }}">
                
                <div class="flex justify-between items-start">
                    <span class="text-sm font-medium {{ $day['isToday'] ? 'bg-accent-500 text-white w-7 h-7 flex items-center justify-center rounded-full -mt-1 -ml-1' : '' }}">
                        {{ $day['day'] }}
                    </span>
                </div>

                <!-- Event Badge Container -->
                @if(count($day['events']) > 0)
                    @php $firstEvent = $day['events'][0]; @endphp
                    <div class="mt-1 relative group/event">
                        <!-- Badge -->
                        <div class="flex items-center gap-1.5 px-2 py-1 rounded-md bg-slate-100 dark:bg-slate-700/50 border border-slate-200/60 dark:border-slate-600/50 shadow-sm truncate">
                            <div class="w-2 h-2 rounded-full flex-shrink-0 {{ $firstEvent['color'] }}"></div>
                            <span class="text-[10px] sm:text-xs font-semibold text-slate-700 dark:text-slate-300 truncate">
                                {{ $firstEvent['title'] }}
                            </span>
                            @if(count($day['events']) > 1)
                                <span class="text-[9px] sm:text-[10px] font-bold text-slate-500 bg-slate-200 dark:bg-slate-600 px-1 rounded flex-shrink-0">
                                    +{{ count($day['events']) - 1 }}
                                </span>
                            @endif
                        </div>

                        <!-- Desktop Hover Popover -->
                        <div class="hidden lg:block absolute z-50 top-full left-1/2 -translate-x-1/2 mt-2 w-56 bg-slate-900 dark:bg-slate-700 text-white text-xs rounded-xl shadow-xl opacity-0 invisible group-hover/event:opacity-100 group-hover/event:visible transition-all duration-200 pointer-events-none p-3 space-y-2 border border-slate-700 dark:border-slate-600">
                            <!-- Arrow -->
                            <div class="absolute -top-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-slate-900 dark:bg-slate-700 border-t border-l border-slate-700 dark:border-slate-600 rotate-45"></div>
                            
                            <div class="relative z-10 max-h-64 overflow-y-auto pr-1">
                                <div class="font-bold text-slate-300 mb-2 border-b border-slate-700 pb-1">{{ $day['date']->format('l, F j') }}</div>
                                @foreach($day['events'] as $event)
                                    <div class="py-1.5 border-b border-slate-800 dark:border-slate-600 last:border-0">
                                        <div class="flex items-start gap-2">
                                            <span class="w-2 h-2 rounded-full flex-shrink-0 mt-1 {{ $event['color'] }}"></span>
                                            <div>
                                                <div class="font-semibold text-white">{{ $event['title'] }}</div>
                                                @if(isset($event['details']) && $event['details'])
                                                    <div class="text-[10px] text-slate-400 mt-0.5 leading-tight">{{ $event['details'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Mobile Bottom Sheet (Alpine controlled) -->
    <div x-show="selectedDay !== null" 
         style="display: none;"
         class="fixed inset-0 z-50 lg:hidden flex flex-col justify-end">
        
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" 
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="selectedDay = null"></div>
             
        <!-- Sheet Content -->
        <div class="relative bg-white dark:bg-slate-800 w-full rounded-t-3xl shadow-2xl p-6 flex flex-col max-h-[80vh]"
             x-transition:enter="transition-transform ease-out duration-300"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition-transform ease-in duration-200"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             @click.stop>
             
            <div class="w-12 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full mx-auto mb-6"></div>
            
            <h3 class="text-xl font-heading font-bold text-slate-900 dark:text-white mb-4">
                <span x-text="selectedDay ? selectedDay.date.split('T')[0] : ''"></span>
            </h3>
            
            <div class="overflow-y-auto flex-1 space-y-3 pb-4">
                <template x-if="selectedDay">
                    <template x-for="event in selectedDay.events" :key="event.id">
                        <div class="flex flex-col gap-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-700/50">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full flex-shrink-0" :class="event.color"></span>
                                <span class="text-sm font-bold text-slate-800 dark:text-slate-200" x-text="event.title"></span>
                            </div>
                            <template x-if="event.details">
                                <span class="text-xs text-slate-500 dark:text-slate-400 pl-6" x-text="event.details"></span>
                            </template>
                        </div>
                    </template>
                </template>
            </div>
            
            <button @click="selectedDay = null" class="mt-2 w-full py-3.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold rounded-xl transition-colors">
                Close
            </button>
        </div>
    </div>
</div>
