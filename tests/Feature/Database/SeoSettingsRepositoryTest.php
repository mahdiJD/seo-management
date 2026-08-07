<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mahdijd\SeoManagement\Contracts\SeoSettingsRepositoryInterface;
use Mahdijd\SeoManagement\Models\SeoSettings;
use Mahdijd\SeoManagement\Repositories\SeoSettingsRepository;

uses(RefreshDatabase::class);

describe('SeoSettingsRepository', function (): void {
    beforeEach(function (): void {
        $this->repository = new SeoSettingsRepository;
    });

    it('implements SeoSettingsRepositoryInterface', function (): void {
        expect($this->repository)->toBeInstanceOf(SeoSettingsRepositoryInterface::class);
    });

    it('auto-creates a global settings record when get() is called on a fresh database', function (): void {
        expect(SeoSettings::count())->toBe(0);

        $settings = $this->repository->get();

        expect($settings)->toBeInstanceOf(SeoSettings::class)
            ->and(SeoSettings::count())->toBe(1);
    });

    it('returns the same existing record on subsequent get() calls', function (): void {
        $first = $this->repository->get();
        $second = $this->repository->get();

        expect($first->id)->toBe($second->id)
            ->and(SeoSettings::count())->toBe(1);
    });

    it('updates global settings record via update()', function (): void {
        $updated = $this->repository->update([
            'site_name' => 'My Super Site',
            'default_title' => 'Default Site Title',
        ]);

        expect($updated->site_name)->toBe('My Super Site')
            ->and($updated->default_title)->toBe('Default Site Title')
            ->and(SeoSettings::count())->toBe(1);
    });
});
