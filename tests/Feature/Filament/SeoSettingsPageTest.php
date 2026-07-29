<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mahdijd\SeoManagement\Contracts\SeoSettingsRepositoryInterface;

uses(RefreshDatabase::class);

describe('SeoSettingsPage', function (): void {
    it('guarantees global settings record exists via repository get()', function (): void {
        $repository = app(SeoSettingsRepositoryInterface::class);

        $settings = $repository->get();

        expect($settings)->not->toBeNull();
    });
});
