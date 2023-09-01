<?php

namespace Hasnayeen\Themes\Http\Middleware;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Filament\Support\Assets\Css;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Hasnayeen\Themes\ThemesPlugin;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentColor;
use Symfony\Component\HttpFoundation\Response;

class SetTheme
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $panel = Filament::getCurrentPanel();
        $panel->userMenuItems(
            ThemesPlugin::canView() ?
            [
                MenuItem::make('Themes')
                    ->label('Themes')
                    ->icon('heroicon-o-swatch')
                    ->url('/themes'),
            ] : []
        );
        FilamentColor::register([
            'primary' => Arr::get(Color::all(), ThemesPlugin::getCurrentThemeColor()) ?? ThemesPlugin::getCurrentThemeColor(),
        ]);
        FilamentAsset::register([
            match (ThemesPlugin::getCurrentTheme()) {
                'sunset' => Css::make('sunset', __DIR__ . '/../resources/dist/sunset.css'),
                default => Css::make('default', __DIR__ . '/../resources/dist/default.css'),
            },
        ], 'hasnayeen/themes');

        return $next($request);
    }
}
