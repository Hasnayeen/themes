<?php

namespace Hasnayeen\Themes\Themes;

use Filament\Support\Colors\Color;
use Hasnayeen\Themes\Contracts\HasChangeableColor;
use Hasnayeen\Themes\Contracts\Theme;
use Illuminate\Support\Arr;

class Sunset implements Theme, HasChangeableColor
{
    public static function getName(): string
    {
        return 'sunset';
    }

    public static function getPublicPath(): string
    {
        return __DIR__ . '/../../resources/dist/sunset.css';
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
