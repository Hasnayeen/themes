<x-filament-panels::page>
    <section class="">
        <header class="flex items-center gap-x-3 overflow-hidden py-4">
            <div class="grid flex-1 gap-y-1">
                <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    {{ __('themes::themes.primary_color') }}
                </h3>

                <p class="fi-section-header-description text-sm text-gray-500 dark:text-gray-400">
                    {{ __('themes::themes.select_base_color') }}
                </p>
            </div>
        </header>

        <div class="flex items-center gap-4 border-t py-6">
            @if ($this->getCurrentTheme() instanceof \Hasnayeen\Themes\Contracts\HasChangeableColor)
                @foreach ($this->getColors() as $name => $color)
                    <button wire:click="setColor('{{ $name }}')" class="w-4 h-4 rounded-full"
                        style="background-color: rgb({{ $color[500] }});">
                    </button>
                @endforeach
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <input type="color" id="custom" name="custom" class="w-4 h-4" wire:input="setColor($event.target.value)" value="" />
                    <label for="custom">{{ __('themes::themes.custom') }}</label>
                </div>
            @else
                <p class="text-gray-700 dark:text-gray-400">{{ __('themes::themes.no_changing_primary_color') }}</p>
            @endif
        </div>
    </section>

    <section class="">
        <header class="flex items-center gap-x-3 overflow-hidden py-4">
            <div class="grid flex-1 gap-y-1">
                <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    {{ __('themes::themes.themes') }}
                </h3>
        
                <p class="fi-section-header-description text-sm text-gray-500 dark:text-gray-400">
                    {{ __('themes::themes.select_interface') }}
                </p>
            </div>
        </header>

        <div class="grid grid-cols-1 gap-6 border-t py-6">
            @foreach ($this->getThemes() as $name => $theme)
                <x-filament::section>
                    <x-slot name="heading">
                        {{ \Illuminate\Support\Str::title($name) }} 
                        @if ($this->getCurrentTheme()->getName() === $name)
                            <span class="ltr:ml-2 rtl:mr-2 text-xs bg-primary-200 text-primary-700 px-2 py-1 rounded">{{ __('themes::themes.active') }}</span>
                        @endif
                    </x-slot>
                    
                    <x-slot name="headerEnd">
                        <x-filament::button wire:click="setTheme('{{ $name }}')" size="xs" outlined>
                            {{ __('themes::themes.select') }}
                        </x-filament::button>
                    </x-slot>

                    @php
                        $noLightMode = in_array(\Hasnayeen\Themes\Contracts\HasOnlyDarkMode::class, class_implements($theme));
                        $noDarkMode = in_array(\Hasnayeen\Themes\Contracts\HasOnlyLightMode::class, class_implements($theme));
                    @endphp
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            @if ($noLightMode)
                                <h3 class="text-sm font-semibold text-gray-600 pb-4">{{ __('themes::themes.no_light_mode') }}</h3>
                            @else
                                <h3 class="text-sm font-semibold text-gray-600 pb-4">{{ __('themes::themes.light') }}</h3>
                                <img src="{{ url('https://raw.githubusercontent.com/Hasnayeen/themes/3.x/assets/'.$name.'-light.png') }}" alt="{{ $name }} theme preview (light version)" class="border dark:border-gray-700 rounded-lg">
                            @endif
                        </div>
        
                        <div>
                            @if ($noDarkMode)
                                <h3 class="text-sm font-semibold text-gray-600 pb-4">{{ __('themes::themes.no_dark_mode') }}</h3>
                            @else
                                <h3 class="text-sm font-semibold text-gray-600 pb-4">{{ __('themes::themes.dark') }}</h3>
                                <img src="{{ url('https://raw.githubusercontent.com/Hasnayeen/themes/3.x/assets/'.$name.'-dark.png') }}" alt="{{ $name }} theme preview (dark version)" class="border dark:border-gray-700 rounded-lg">
                            @endif
                        </div>
                    </div>
                </x-filament::section>
            @endforeach
        </div>
    </section>
</x-filament-panels::page>
