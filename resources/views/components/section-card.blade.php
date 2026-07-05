@props(['title' => null, 'action' => null])

<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    @if($title)
        <div class="px-6 py-5 border-b border-slate-100/80 flex items-center justify-between bg-slate-50/30">
            <h3 class="text-lg font-bold text-slate-900 tracking-tight">{{ $title }}</h3>
            @if($action)
                {!! $action !!}
            @endif
        </div>
    @endif
    <div class="p-6 sm:p-8">
        {{ $slot }}
    </div>
</div>
