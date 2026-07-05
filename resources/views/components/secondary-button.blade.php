<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-white border border-slate-300 rounded-lg font-semibold text-sm text-slate-700 tracking-wide shadow-sm hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:ring-offset-2 disabled:opacity-25 transition-all ease-in-out duration-200']) }}>
    {{ $slot }}
</button>
