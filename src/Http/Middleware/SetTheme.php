<?php

namespace Hasnayeen\Themes\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentColor;
use Hasnayeen\Themes\Contracts\CanModifyPanelConfig;
use Hasnayeen\Themes\Filament\Pages\Themes as ThemesPage;
use Hasnayeen\Themes\Themes;
use Hasnayeen\Themes\Themes\DefaultTheme;
use Hasnayeen\Themes\Themes\Nord;
use Hasnayeen\Themes\Themes\Sunset;
use Hasnayeen\Themes\ThemesPlugin;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTheme
{
    public function handle(Request $request, Closure $next): Response
    {
        $themes = app(Themes::class);
        $panel = Filament::getCurrentPanel();

        if (! $panel->hasPlugin('themes')) {
            return $next($request);
        }

        $panel->userMenuItems(
            ThemesPlugin::canView() ?
            [
                MenuItem::make('Themes')
                    ->label(__('themes::themes.themes'))
                    ->icon('heroicon-o-swatch')
                    ->url(ThemesPage::getUrl()),
            ] : []
        );
        FilamentColor::register($themes->getCurrentThemeColor());
        FilamentAsset::register([
            match (get_class($themes->getCurrentTheme())) {
                Nord::class => Css::make(Nord::getName(), Nord::getPath()),
                Sunset::class => Css::make(Sunset::getName(), Sunset::getPath()),
                default => Css::make(DefaultTheme::getName(), DefaultTheme::getPath()),
            },
        ], 'hasnayeen/themes');
        if ($themes->getCurrentTheme() instanceof CanModifyPanelConfig) {
            $themes->getCurrentTheme()->modifyPanelConfig($panel);
        }

        return $next($request);
    }
}
