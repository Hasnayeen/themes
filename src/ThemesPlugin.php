<?php

namespace Hasnayeen\Themes;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Hasnayeen\Themes\Filament\Pages\Themes as ThemesPage;

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
            ThemesPage::class,
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

    public function canViewThemesPage(Closure $callback): self
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

    public function registerTheme(array $theme, bool $override = false): self
    {
        app(Themes::class)->register($theme, $override);

        return $this;
    }
}
