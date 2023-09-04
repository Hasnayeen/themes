<?php

namespace Hasnayeen\Themes\Contracts;

use Filament\Panel;

interface CanModifyPanelConfig
{
    public function modifyPanelConfig(Panel $panel): Panel;
}
