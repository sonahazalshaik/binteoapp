@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'help' => null,
    'class' => '',
    'errorBag' => null,
])

<div class="mb-5 sm:mb-8">
    @if($label)
        <label for="{{ $name }}" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30 px-1 mb-2">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    
    <div class="relative" x-data="{ focused: false, showPassword: false }">
        <div class="relative">
            @if($attributes->has('icon'))
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-300 transition-colors z-10 pointer-events-none" :class="focused ? '!text-orange-500' : ''">
                    @if(str_starts_with($attributes->get('icon'), 'la '))
                        <i class="{{ $attributes->get('icon') }} text-xl"></i>
                    @else
                        <span class="material-symbols-rounded text-xl">
                            {{ $attributes->get('icon') }}
                        </span>
                    @endif
                </div>
            @endif

            <input 
                :type="showPassword ? 'text' : '{{ $type }}'" 
                name="{{ $name }}" 
                id="{{ $name }}"
                value="{{ old($name, $value) }}"
                placeholder="{{ $placeholder }}"
                @focus="focused = true"
                @blur="focused = false"
                @if($required) required @endif
                {{ $attributes->merge(['class' => 'w-full h-12 sm:h-16 ' . ($attributes->has('icon') ? 'pl-12' : 'px-6') . ' pr-12 rounded-2xl border transition-all duration-300 outline-none text-[13px] font-black text-slate-900 dark:text-white ' . ($errors->getBag($errorBag ?? 'default')->has($name) ? 'border-red-500 bg-red-50 dark:bg-red-900/10 focus:ring-4 focus:ring-red-500/10' : 'border-slate-200/60 dark:border-white/5 bg-white dark:bg-black/20 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10')]) }}
            >

            @if($type === 'password')
                <button type="button" @click="showPassword = !showPassword" tabindex="-1" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-orange-500 transition-colors focus:outline-none">
                    <span class="material-symbols-rounded text-xl" x-text="showPassword ? 'visibility_off' : 'visibility'">visibility</span>
                </button>
            @endif

            @if(isset($append))
                {{ $append }}
            @endif
        </div>
        
        @if($attributes->has('hint'))
            <div x-show="focused" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="mt-2 text-[10px] font-bold text-orange-500 uppercase tracking-widest flex items-center gap-1">
                <span class="material-symbols-rounded text-xs">lightbulb</span>
                {{ $attributes->get('hint') }}
            </div>
        @endif

        @if($errors->getBag($errorBag ?? 'default')->has($name))
            <div class="mt-1 text-xs text-red-500 font-medium flex items-center gap-1">
                <span class="material-symbols-rounded text-sm">error</span>
                {{ $errors->getBag($errorBag ?? 'default')->first($name) }}
            </div>
        @endif
    </div>

    @if($help)
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif
</div>
