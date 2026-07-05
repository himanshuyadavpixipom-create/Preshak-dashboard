@props(['title', 'value', 'icon' => null, 'trend' => null])

<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 flex items-start justify-between hover:shadow-md transition-shadow duration-200">
    <div>
        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ $title }}</p>
        <p class="text-4xl font-extrabold text-slate-900 mt-2.5 tracking-tight">{{ $value }}</p>
        @if($trend)
            <div class="inline-flex items-center mt-3 text-xs font-semibold text-primary-700 bg-primary-50 px-2.5 py-1 rounded-full border border-primary-100">
                {{ $trend }}
            </div>
        @endif
    </div>
    @if($icon)
        <div class="p-3.5 bg-white shadow-sm ring-1 ring-slate-900/5 text-slate-600 rounded-xl">
            {!! $icon !!}
        </div>
    @endif
</div>
