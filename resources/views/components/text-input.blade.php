@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900/50 text-slate-900 dark:text-white focus:ring-accent-500 focus:border-accent-500 dark:focus:ring-accent-500 dark:focus:border-accent-500 transition-all shadow-sm placeholder-slate-400 dark:placeholder-slate-500 sm:text-sm']) }}>
