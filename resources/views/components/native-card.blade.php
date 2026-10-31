@props([
    'link' => '#',
    'title' => '',
    'value' => '0',
    'icon' => 'las la-users',
    'color' => 'primary', // primary, success, danger, warning, info
    'viewMore' => false
])

@php
    $colors = [
        'primary' => 'border-indigo-100 bg-indigo-50 text-indigo-600',
        'success' => 'border-emerald-100 bg-emerald-50 text-emerald-600',
        'danger'  => 'border-rose-100 bg-rose-50 text-rose-600',
        'warning' => 'border-amber-100 bg-amber-50 text-amber-600',
        'info'    => 'border-blue-100 bg-blue-50 text-blue-600',
        'purple'  => 'border-purple-100 bg-purple-50 text-purple-600',
    ];
    $colorClass = $colors[$color] ?? $colors['primary'];
@endphp

<a href="{{ $link }}" class="group block p-5 {{ $colorClass }} dark:bg-opacity-10 rounded-xl border shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 rounded-xl bg-white dark:bg-black/20 flex items-center justify-center text-2xl shadow-sm border border-inherit">
                <i class="{{ $icon }}"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-0.5">{{ __($title) }}</p>
                <h3 class="text-base font-bold text-slate-800 dark:text-white leading-tight">{{ $value }}</h3>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if($viewMore)
                <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-tighter opacity-0 group-hover:opacity-100 transition-all">View All</span>
            @endif
            <i class="las la-angle-right text-xl text-slate-400 dark:text-slate-500 group-hover:text-indigo-500 group-hover:translate-x-1 transition-all"></i>
        </div>
    </div>
</a>
