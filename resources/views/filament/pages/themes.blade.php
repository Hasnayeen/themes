<x-filament-panels::page>
    <section class="">
        <header class="flex items-center gap-x-3 overflow-hidden py-4">
            <div class="grid flex-1 gap-y-1">
                <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    Primary Color
                </h3>

                <p class="fi-section-header-description text-sm text-gray-500 dark:text-gray-400">
                    Select base color for your theme.
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
                <div class="flex items-center space-x-4">
                    <input type="color" id="custom" name="custom" class="w-4 h-4" wire:input="setColor($event.target.value)" value="" />
                    <label for="custom">Custom</label>
                </div>
            @else
                <p class="text-gray-700">These theme does not support changing primary color.</p>
            @endif
        </div>
    </section>

    <section class="">
        <header class="flex items-center gap-x-3 overflow-hidden py-4">
            <div class="grid flex-1 gap-y-1">
                <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    Themes
                </h3>
        
                <p class="fi-section-header-description text-sm text-gray-500 dark:text-gray-400">
                    Select how you would like your interface to look.
                </p>
            </div>
        </header>

        <div class="grid grid-cols-1 gap-6 border-t py-6">
            @foreach ($this->getThemes() as $name => $theme)
                <x-filament::section>
                    <x-slot name="heading">
                        {{ ucfirst($name) }} 
                        @if ($this->getCurrentTheme()->getName() === $name)
                            <span class="ml-2 text-xs bg-primary-200 text-primary-700 px-2 py-1 rounded">Active</span>
                        @endif
                    </x-slot>
                    
                    <x-slot name="headerEnd">
                        <x-filament::button wire:click="setTheme('{{ $name }}')" size="xs" outlined>
                            Select
                        </x-filament::button>
                    </x-slot>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-600 pb-4">Light</h3>
                            <img src="{{ asset('vendor/themes/images/'.$name.'-light.png') }}" alt="{{ $name }} theme preview (light version)" class="border dark:border-gray-700 rounded-lg">
                        </div>
        
                        <div>
                            <h3 class="text-sm font-semibold text-gray-600 pb-4">Dark</h3>
                            <img src="{{ asset('vendor/themes/images/'.$name.'-dark.png') }}" alt="{{ $name }} theme preview (dark version)" class="border dark:border-gray-700 rounded-lg">
                        </div>
                    </div>
                </x-filament::section>
            @endforeach
        </div>
    </section>
</x-filament-panels::page>
