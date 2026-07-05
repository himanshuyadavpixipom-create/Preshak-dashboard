@props(['title', 'description', 'icon' => null, 'action' => null])

<div class="flex flex-col items-center justify-center text-center p-12 bg-slate-50/50 rounded-2xl border-2 border-dashed border-slate-200/80">
    @if($icon)
        <div class="p-4 bg-white shadow-sm ring-1 ring-slate-900/5 rounded-2xl text-slate-400 mb-5">
            {!! $icon !!}
        </div>
    @endif
    <h3 class="text-lg font-bold text-slate-900 tracking-tight">{{ $title }}</h3>
    <p class="mt-2 text-sm text-slate-500 max-w-sm mx-auto leading-relaxed">{{ $description }}</p>
    @if($action)
        <div class="mt-6">
            {!! $action !!}
        </div>
    @endif
</div>
