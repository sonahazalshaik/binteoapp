@php
    $active = Route::has($item['route']) && request()->routeIs($item['route']);
@endphp

<a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}" 
   class="flex items-center space-x-4 px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ $active ? 'bg-red-500 text-white shadow-lg shadow-red-500/20 active-menu-item' : 'text-gray-700 dark:text-[#F1F1F1] hover:bg-gray-50 dark:hover:bg-white/5' }}">
    <span class="material-symbols-rounded text-[22px] {{ $active ? 'text-white' : 'text-gray-400 group-hover:text-red-500' }}">
        {{ $item['icon'] }}
    </span>
    <span class="font-bold text-[14px] {{ $active ? 'text-white' : '' }}">{{ $item['label'] }}</span>
    @if($active)
        <div class="ml-auto w-1.5 h-1.5 bg-white rounded-full"></div>
    @endif
</a>
