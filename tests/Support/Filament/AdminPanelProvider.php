<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Tests\Support\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Mahdijd\SeoManagement\Filament\SeoPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->default()
            ->plugins([
                SeoPlugin::make(),
            ]);
    }
}
