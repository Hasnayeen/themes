<?php

namespace Hasnayeen\Themes;

use Filament\Facades\Filament;
use Hasnayeen\Themes\Contracts\HasChangeableColor;
use Hasnayeen\Themes\Contracts\Theme;
use Hasnayeen\Themes\Themes\DefaultTheme;
use Hasnayeen\Themes\Themes\Dracula;
use Hasnayeen\Themes\Themes\Nord;
use Hasnayeen\Themes\Themes\Sunset;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Themes
{
    protected $collection;

    public function __construct()
    {
        $this->collection = collect([
            DefaultTheme::getName() => DefaultTheme::class,
            Dracula::getName() => Dracula::class,
            Nord::getName() => Nord::class,
            Sunset::getName() => Sunset::class,
        ]);
    }

    public function getThemes(): Collection
    {
        return $this->collection;
    }

    public function register(array $themes, bool $override = false): self
    {
        if ($override) {
            $this->collection = collect($themes);

            return $this;
        }
        $this->collection = $this->collection->merge($themes);

        return $this;
    }

    public function make(string $theme): Theme
    {
        $name = $this->collection->first(fn ($item) => $item::getName() === $theme);
        if ($name) {
            return new $name;
        }
    }

    public function getCurrentTheme(): Theme
    {
        if (config('themes.mode') === 'global') {
            return $this->make(cache('theme') ?? 'default');
        }

        return $this->make(Filament::getCurrentPanel()->auth()->user()->theme ?? 'default');
    }

    public function getCurrentThemeColor(): array
    {
        if (! $this->getCurrentTheme() instanceof HasChangeableColor) {
            return $this->getCurrentTheme()->getThemeColor();
        }

        if (config('themes.mode') === 'global') {
            $color = cache('theme_color') ?? null;
        } else {
            $color = Filament::getCurrentPanel()->auth()->user()->theme_color ?? null;
        }

        return Arr::has($this->getCurrentTheme()->getThemeColor(), $color)
            ? ['primary' => Arr::get($this->getCurrentTheme()->getThemeColor(), $color)]
            : $this->getCurrentTheme()->getPrimaryColor();
    }
}
