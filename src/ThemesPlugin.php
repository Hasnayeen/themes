<?php

namespace Hasnayeen\Themes;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Panel;
use Hasnayeen\Themes\Filament\Pages\Themes;

class ThemesPlugin implements Plugin
{
    protected Closure $canViewCallback;

    public function getId(): string
    {
        return 'themes';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            Themes::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function canViewThemesPage(Closure $callback): static
    {
        $this->canViewCallback = $callback;

        return $this;
    }

    public static function canView(): bool
    {
        if (isset(static::get()->canViewCallback)) {
            return (static::get()->canViewCallback)();
        }

        return true;
    }

    public static function getCurrentTheme(): string
    {
        if (config('themes.mode') === 'global') {
            return cache('theme') ?? 'default';
        }

        return Filament::getCurrentPanel()->auth()->user()->theme ?? 'default';
    }

    public static function getCurrentThemeColor(): string
    {
        if (config('themes.mode') === 'global') {
            return cache('theme_color') ?? 'blue';
        }

        return Filament::getCurrentPanel()->auth()->user()->theme_color ?? 'blue';
    }
}
