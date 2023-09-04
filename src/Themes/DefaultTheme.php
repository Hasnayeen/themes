<?php

namespace Hasnayeen\Themes\Themes;

use Illuminate\Support\Arr;
use Filament\Support\Colors\Color;
use Hasnayeen\Themes\Contracts\Theme;

class DefaultTheme implements Theme
{
    public static function getName(): string
    {
        return 'default';
    }

    public static function getPublicPath(): string
    {
        return __DIR__ . '/../../resources/dist/default.css';
    }

    public function getThemeColor(): array
    {
        return Arr::except(Color::all(), ['gray', 'zinc', 'neutral', 'stone']);
    }

    public function getPrimaryColor(): array
    {
        return ['primary' => Color::rgb($this->getThemeColor()['blue'])];
    }
}
