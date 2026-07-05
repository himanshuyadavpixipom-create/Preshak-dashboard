<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-accent-600 hover:bg-accent-700 dark:bg-accent-500 dark:hover:bg-accent-600 border border-transparent rounded-xl font-bold text-sm text-white tracking-wide focus:outline-none focus:ring-2 focus:ring-accent-500/50 dark:focus:ring-accent-500/50 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition-all ease-in-out duration-200 shadow-sm']) }}>
    {{ $slot }}
</button>
