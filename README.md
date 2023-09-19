# Themes for Filament panels

![preview](https://raw.githubusercontent.com/Hasnayeen/themes/3.x/assets/preview.png)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hasnayeen/themes.svg?style=flat-square)](https://packagist.org/packages/hasnayeen/themes)
[![Total Downloads](https://img.shields.io/packagist/dt/hasnayeen/themes.svg?style=flat-square)](https://packagist.org/packages/hasnayeen/themes)

`Themes` is a Filament plugin that allows users to set themes from a collection and customize the color of the selected theme. The package provides a simple and easy-to-use interface for selecting and applying themes to Filament panels.

## Available For Hire

For custom theme please reach out via [email](mailto:searching.nehal@gmail.com) or [discord](https://discordapp.com/users/297318343642447872)

I'm also available for contractual work on this stack (Filament, Laravel, Livewire, AlpineJS, TailwindCSS). Reach me via [email](searching.nehal@gmail.com) or [discord](discordapp.com/users/297318343642447872)

## Installation

You can install the package via composer:

```bash
composer require hasnayeen/themes
```

Publish plugin assets by running following commands

```bash
php artisan vendor:publish --tag="themes-assets"
```

If you want to set theme per user then you'll need to run the package migration. You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="themes-migrations"
php artisan migrate
```

_You need to publish config file and change `'mode' => 'user'` in order to set theme for user separately_

You can publish the config file with:

```bash
php artisan vendor:publish --tag="themes-config"
```

This is the contents of the published config file:

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Theme mode
    |--------------------------------------------------------------------------
    |
    | This option determines how the theme will be set for the application.
    | By default global mode is set to use one theme for all user. If you
    |  want to set theme for each user separately, then set to 'user'.
    |
    */

    'mode' => 'global',

    /*
    |--------------------------------------------------------------------------
    | Theme Icon
    |--------------------------------------------------------------------------
    */

    'icon' => 'heroicon-o-swatch',

];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="themes-views"
```

## Usage

You'll have to register the plugin in your panel provider

```php
    public function panel(Panel $panel): Panel
    {
        return $panel
            ...
            ->plugin(
                \Hasnayeen\Themes\ThemesPlugin::make()
            );
    }
```

Add `Hasnayeen\Themes\Http\Middleware\SetTheme` middleware to your provider `middleware` method or if you're using filament multi-tenancy then instead add to `tenantMiddleware` method.

```php
    public function panel(Panel $panel): Panel
    {
        return $panel
            ...
            ->middleware([
                ...
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class
            ])
            // or in `tenantMiddleware` if you're using multi-tenancy
            ->tenantMiddleware([
                ...
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class
            ])
    }
```

This plugin provides a themes setting page. You can visit the page from user menu.

![page-menu-link](https://raw.githubusercontent.com/Hasnayeen/themes/3.x/assets/page-menu-link.png)

## Authorization

You can configure the authorization of themes settings page and user menu option by providing a closure to the `canViewThemesPage` method on `ThemesPlugin`.

```php
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->plugin(
                \Hasnayeen\Themes\ThemesPlugin::make()
                    ->canViewThemesPage(fn () => auth()->user()?->is_admin)
            );
    }
```

## Customize theme collection

You can [create new custom theme](#create-custom-theme) and register them via `registerTheme` method on plugin.

```php
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->plugin(
                \Hasnayeen\Themes\ThemesPlugin::make()
                    ->registerTheme([MyCustomTheme::getName() => MyCustomTheme::class])
            );
    }
```

You can also remove plugins default theme set by providing `override` argument as true. You may choose to pick some of the themes from plugin theme set.

```php
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->plugin(
                \Hasnayeen\Themes\ThemesPlugin::make()
                    ->registerTheme(
                        [
                            MyCustomTheme::class,
                            \Hasnayeen\Themes\Themes\Sunset::class,
                        ],
                        override: true,
                    )
            );
    }
```

## Create custom theme

You can create custom theme and [register](#customize-theme-collection) them in themes plugin. To create a new theme run following command in the terminal and follow the steps

```bash
php artisan themes:make Awesome --panel=App
```

This will create the following class

```php
use Filament\Panel;
use Hasnayeen\Themes\Contracts\CanModifyPanelConfig;
use Hasnayeen\Themes\Contracts\Theme;

class Awesome implements CanModifyPanelConfig, Theme
{
    public static function getName(): string
    {
        return 'awesome';
    }

    public static function getPublicPath(): string
    {
        return 'resources/css/filament/app/themes/awesome.css';
    }

    public function getThemeColor(): array
    {
        return [
            'primary' => '#000',
            'secondary' => '#fff',
        ];
    }

    public function modifyPanelConfig(Panel $panel): Panel
    {
        return $panel
            ->viteTheme($this->getPath());
    }
}
```

If your theme support changing primary color then implement `Hasnayeen\Themes\Contracts\HasChangeableColor` interface and `getPrimaryColor` method.

If your theme need to change panel config then do so inside `modifyPanelConfig` method in your theme.

```php
use Hasnayeen\Themes\Contracts\CanModifyPanelConfig;
use Hasnayeen\Themes\Contracts\Theme;

class Awesome implement CanModifyPanelConfig, Theme
{
    public function modifyPanelConfig(Panel $panel): Panel
    {
        return $panel
            ->viteTheme($this->getPath())
            ->topNavigation();
    }
}
```

Next add a new item to the `input` array of `vite.config.js`: `resources/css/awesome.css`

## Available Themes

Dracula (dark)

![dracula-dark](https://raw.githubusercontent.com/Hasnayeen/themes/3.x/assets/dracula-dark.png)

Nord (light)

![nord-light](https://raw.githubusercontent.com/Hasnayeen/themes/3.x/assets/nord-light.png)

Nord (dark)

![nord-dark](https://raw.githubusercontent.com/Hasnayeen/themes/3.x/assets/nord-dark.png)

Sunset (light)

![sunset-light](https://raw.githubusercontent.com/Hasnayeen/themes/3.x/assets/sunset-light.png)

Sunset (dark)

![sunset-dark](https://raw.githubusercontent.com/Hasnayeen/themes/3.x/assets/sunset-dark.png)

## Upgrading

Everytime you update the package you should run package upgrade command so that necessary assets have been published.

```bash
composer update

php artisan themes:upgrade
```

Alternatively you can add this command to `composer.json` on `post-autoload-dump` hook so that upgrade command run automatically after every update.

```json
"post-autoload-dump": [
    // ...
    "@php artisan themes:upgrade"
],
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Hasnayeen](https://github.com/Hasnayeen)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
