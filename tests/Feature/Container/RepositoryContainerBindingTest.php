<?php

declare(strict_types=1);

use Mahdijd\SeoManagement\Contracts\SeoMetadataRepositoryInterface;
use Mahdijd\SeoManagement\Contracts\SeoRouteRepositoryInterface;
use Mahdijd\SeoManagement\Contracts\SeoSettingsRepositoryInterface;
use Mahdijd\SeoManagement\Repositories\SeoMetadataRepository;
use Mahdijd\SeoManagement\Repositories\SeoRouteRepository;
use Mahdijd\SeoManagement\Repositories\SeoSettingsRepository;

describe('Repository Container Bindings', function (): void {
    it('resolves SeoMetadataRepositoryInterface to SeoMetadataRepository singleton', function (): void {
        $first  = app(SeoMetadataRepositoryInterface::class);
        $second = app(SeoMetadataRepositoryInterface::class);

        expect($first)->toBeInstanceOf(SeoMetadataRepository::class)
            ->and($first)->toBe($second);
    });

    it('resolves SeoRouteRepositoryInterface to SeoRouteRepository singleton', function (): void {
        $first  = app(SeoRouteRepositoryInterface::class);
        $second = app(SeoRouteRepositoryInterface::class);

        expect($first)->toBeInstanceOf(SeoRouteRepository::class)
            ->and($first)->toBe($second);
    });

    it('resolves SeoSettingsRepositoryInterface to SeoSettingsRepository singleton', function (): void {
        $first  = app(SeoSettingsRepositoryInterface::class);
        $second = app(SeoSettingsRepositoryInterface::class);

        expect($first)->toBeInstanceOf(SeoSettingsRepository::class)
            ->and($first)->toBe($second);
    });
});
