<?php

namespace Hasnayeen\Themes\Commands;

use Filament\Support\Facades\FilamentAsset;
use Illuminate\Console\Command;

class UpgradeCommand extends Command
{
    protected $description = 'Upgrade themes to the latest version';

    protected $signature = 'themes:upgrade';

    public function handle(): int
    {
        foreach (FilamentAsset::getStyles(['hasnayeen/themes']) as $asset) {
            $this->copyAsset($asset->getPath(), $asset->getPublicPath());
        }

        $this->components->info('Successfully upgraded!');

        return static::SUCCESS;
    }
}
