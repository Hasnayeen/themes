<?php

namespace Hasnayeen\Themes\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Colors\Color;
use Hasnayeen\Themes\ThemesPlugin;
use Illuminate\Support\Arr;

class Themes extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $title = 'Appearance';

    protected static string $view = 'themes::filament.pages.themes';

    public function mount(): void
    {
        abort_unless(ThemesPlugin::canView(), 403);
    }

    public function getThemes()
    {
        return config('themes.collection');
    }

    public function getColors()
    {
        return Arr::except(Color::all(), ['gray', 'zinc', 'neutral', 'stone']);
    }

    public function setColor(string $color)
    {
        if (config('themes.mode') === 'global') {
            cache(['theme_color' => $color]);
        } else {
            Filament::auth()->user()->update(['theme_color' => $color]);
        }

        Notification::make()
            ->title('Theme primary color set to ' . $color . '.')
            ->success()
            ->send();

        return $this->redirect(self::getUrl());
    }

    public function setTheme(string $theme)
    {
        if (config('themes.mode') === 'global') {
            cache(['theme' => $theme]);
        } else {
            Filament::auth()->user()->update(['theme' => $theme]);
        }

        Notification::make()
            ->title('Theme set to ' . $theme . '.')
            ->success()
            ->send();

        return $this->redirect(self::getUrl());
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
