# Themes for Filament panels

![preview](https://raw.githubusercontent.com/Hasnayeen/themes/3.x/assets/preview.png)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hasnayeen/themes.svg?style=flat-square)](https://packagist.org/packages/hasnayeen/themes)
[![Total Downloads](https://img.shields.io/packagist/dt/hasnayeen/themes.svg?style=flat-square)](https://packagist.org/packages/hasnayeen/themes)

`Themes` is a Filament plugin that allows users to set themes from a collection and customize the color of the selected theme. The package provides a simple and easy-to-use interface for selecting and applying themes to Filament panels.

## Hire me

I'm available for contractual work on this stack (Filament, Laravel, Livewire, AlpineJS, TailwindCSS). Reach me via [email](searching.nehal@gmail.com) or [discord](discordapp.com/users/297318343642447872)

## Installation

You can install the package via composer:

```bash
composer require hasnayeen/themes
```

Publish plugin assets by running following commands

```bash
php artisan vendor:publish --tag="themes-assets"
```

If you want to set theme per user than you'll need to run the package migration. You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="themes-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="themes-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="themes-views"
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

    'mode' => 'user',

    /*
    |--------------------------------------------------------------------------
    | Themes collection
    |--------------------------------------------------------------------------
    |
    | This option determines the available themes for the application. If you
    | want remove any theme from your theme switcher, just remove the theme
    | from this array. You can also add your own theme to this array.
    |
    */

    'collection' => [
        'default' => 'Default',
        'sunset' => 'Sunset',
    ],

];
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
                Hasnayeen\Themes\ThemesPlugin::make()
                    ->canViewThemesPage(fn () => auth()->user()->is_admin)
            );
    }
```

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
