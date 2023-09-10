<?php

namespace Hasnayeen\Themes\Themes;

use Filament\Support\Colors\Color;
use Hasnayeen\Themes\Contracts\HasChangeableColor;
use Hasnayeen\Themes\Contracts\Theme;
use Illuminate\Support\Arr;

class DefaultTheme implements HasChangeableColor, Theme
{
    public static function getName(): string
    {
        return 'default';
    }

    public static function getPath(): string
    {
        return __DIR__ . '/../../resources/dist/default.css';
    }

    public function getThemeColor(): array
    {
        return Arr::except(Color::all(), ['gray', 'zinc', 'neutral', 'stone']);
    }

    public function getPrimaryColor(): array
    {
        return ['primary' => $this->getThemeColor()['blue']];
    }
}
