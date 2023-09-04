<?php

namespace Hasnayeen\Themes\Contracts;

interface Theme
{
    public static function getName(): string;

    public static function getPublicPath(): string;

    public function getThemeColor(): array;
}
