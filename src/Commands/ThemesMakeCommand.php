<?php

namespace Hasnayeen\Themes\Commands;

use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class ThemesMakeCommand extends Command
{
    use CanManipulateFiles;

    protected $description = 'Create a new theme class';

    protected $signature = 'themes:make {name?} {--panel=} {--F|force}';

    public function handle(): int
    {
        $theme = (string) str(
            $this->argument('name') ??
            text(
                label: 'What is the theme name?',
                placeholder: 'AwesomeTheme',
                required: true,
            ),
        )
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');
        $themeClass = (string) str($theme)->afterLast('\\');
        $themeNamespace = str($theme)->contains('\\') ?
            (string) str($theme)->beforeLast('\\') :
            '';
        $name = Str::kebab($themeClass);

        $panel = $this->option('panel');

        if ($panel) {
            $panel = Filament::getPanel($panel);
        }

        if (! $panel) {
            $panels = Filament::getPanels();

            /** @var Panel $panel */
            $panel = (count($panels) > 1) ? $panels[select(
                label: 'Which panel would you like to create this in?',
                options: array_map(
                    fn (Panel $panel): string => $panel->getId(),
                    $panels,
                ),
                default: Filament::getDefaultPanel()->getId()
            )] : Arr::first($panels);
        }

        $panelId = $panel->getId();

        $this->createCssFile($theme, $panel, $panelId, $name);

        $path = app_path('Filament/' . ($panel->getPath() ? Str::title($panel->getPath()) . '/' : '') . 'Themes');
        $namespace = 'App\\Filament\\' . ($panel->getPath() ? Str::title($panel->getPath()) . '\\' : '') . 'Themes';

        $path = (string) str($theme)
            ->prepend('/')
            ->prepend($path ?? '')
            ->replace('\\', '/')
            ->replace('//', '/')
            ->append('.php');

        if (! $this->option('force') && $this->checkForCollision([$path])) {
            return static::INVALID;
        }

        $this->copyStubToApp('Theme', $path, [
            'class' => $themeClass,
            'name' => $name,
            'panel' => $panelId,
            'namespace' => str($namespace ?? '') . ($themeNamespace !== '' ? "\\{$themeNamespace}" : ''),
            'method' => file_exists(base_path('vite.config.js')) ? 'viteTheme' : 'theme',
        ]);

        $this->components->info("<fg=green>Successfully created {$themeNamespace}/{$theme}.php!");

        $this->components->warn('Action is required to complete the theme setup:');
        $this->components->bulletList([
            "Add a new item to the `input` array of `vite.config.js`: `<fg=magenta>resources/css/filament/{$panelId}/themes/{$theme}.css</>`.",
            "Make sure to register the theme in `<fg=magenta>ThemesPlugin::registerTheme([{$themeClass}::getName() => {$themeClass}::class])`</></>",
            'Finally, run `npm run build` to compile the theme.',
        ]);

        return static::SUCCESS;
    }

    private function createCssFile($theme, $panel, $panelId, $name): int
    {
        exec('npm -v', $npmVersion, $npmVersionExistCode);

        if ($npmVersionExistCode !== 0) {
            $this->error('Node.js is not installed. Please install before continuing.');

            return static::FAILURE;
        }

        $this->info("Using NPM v{$npmVersion[0]}");

        exec('npm install tailwindcss @tailwindcss/forms @tailwindcss/typography postcss autoprefixer --save-dev');

        $cssFilePath = resource_path("css/filament/{$panelId}/themes/{$name}.css");
        $tailwindConfigFilePath = resource_path("css/filament/{$panelId}/themes/tailwind.{$name}.config.js");

        if (! $this->option('force') && $this->checkForCollision([
            $cssFilePath,
            $tailwindConfigFilePath,
        ])) {
            return static::INVALID;
        }

        $classPathPrefix = (string) str(Arr::first($panel->getPageDirectories()))
            ->afterLast('Filament/')
            ->beforeLast('Pages');

        $viewPathPrefix = str($classPathPrefix)
            ->explode('/')
            ->map(fn ($segment) => Str::lower(Str::kebab($segment)))
            ->implode('/');

        $this->copyStubToApp('ThemeCss', $cssFilePath, [
            'panel' => $panelId,
            'theme' => $theme,
        ]);
        $this->copyStubToApp('ThemeTailwindConfig', $tailwindConfigFilePath, [
            'classPathPrefix' => $classPathPrefix,
            'viewPathPrefix' => $viewPathPrefix,
        ]);

        $this->components->info("<fg=green>Successfully created resources/css/filament/{$panelId}/themes/{$theme}.css and resources/css/filament/{$panelId}/themes/tailwind.{$theme}.config.js!</>");

        if (! file_exists(base_path('vite.config.js'))) {
            $this->components->warn('Action is required to complete the theme setup:');
            $this->components->bulletList([
                "It looks like you don't have Vite installed. Please use your asset bundling system of choice to compile `resources/css/filament/{$panelId}/themes/{$theme}.css` into `public/css/filament/{$panelId}/themes/{$theme}.css`.",
                "If you're not currently using a bundler, we recommend using Vite. Alternatively, you can use the Tailwind CLI with the following command:",
                "npx tailwindcss --input ./resources/css/filament/{$panelId}/themes/{$theme}.css --output ./public/css/filament/{$panelId}/themes/{$theme}.css --config ./resources/css/filament/{$panelId}/themes/tailwind.{$theme}.config.js --minify",
                "Make sure to register the theme in the {$theme} class inside `modifyPanelConfig` using `->theme(asset('css/filament/{$panelId}/themes/{$theme}.css'))`",
            ]);

            return static::SUCCESS;
        }

        $postcssConfigPath = base_path('postcss.config.js');

        if (! file_exists($postcssConfigPath)) {
            $this->copyStubToApp('ThemePostcssConfig', $postcssConfigPath);

            $this->components->info('Successfully created postcss.config.js!');
        }

        return static::SUCCESS;
    }
}
