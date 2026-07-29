<?php

declare(strict_types=1);

use Mahdijd\SeoManagement\Filament\SeoPlugin;

describe('SeoPlugin', function (): void {
    it('returns correct plugin identifier', function (): void {
        $plugin = SeoPlugin::make();

        expect($plugin->getId())->toBe('seo-management');
    });
});
