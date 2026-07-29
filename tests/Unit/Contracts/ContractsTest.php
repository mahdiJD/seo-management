<?php

declare(strict_types=1);

use Mahdijd\SeoManagement\Contracts\SeoCacheManagerInterface;
use Mahdijd\SeoManagement\Contracts\SeoMetadataRepositoryInterface;
use Mahdijd\SeoManagement\Contracts\SeoRendererInterface;
use Mahdijd\SeoManagement\Contracts\SeoResolverInterface;
use Mahdijd\SeoManagement\Contracts\SeoRouteRepositoryInterface;
use Mahdijd\SeoManagement\Contracts\SeoSettingsRepositoryInterface;

describe('Contracts / Interfaces', function (): void {
    it('defines SeoResolverInterface with resolve method signature', function (): void {
        $reflection = new ReflectionClass(SeoResolverInterface::class);

        expect($reflection->isInterface())->toBeTrue()
            ->and($reflection->hasMethod('resolve'))->toBeTrue();

        $method = $reflection->getMethod('resolve');
        expect($method->getNumberOfParameters())->toBe(1)
            ->and($method->getParameters()[0]->getName())->toBe('context');
    });

    it('defines SeoRendererInterface with render method signature', function (): void {
        $reflection = new ReflectionClass(SeoRendererInterface::class);

        expect($reflection->isInterface())->toBeTrue()
            ->and($reflection->hasMethod('render'))->toBeTrue();

        $method = $reflection->getMethod('render');
        expect($method->getNumberOfParameters())->toBe(1)
            ->and($method->getParameters()[0]->getName())->toBe('data');
    });

    it('defines SeoCacheManagerInterface with remember, forget, and flush methods', function (): void {
        $reflection = new ReflectionClass(SeoCacheManagerInterface::class);

        expect($reflection->isInterface())->toBeTrue()
            ->and($reflection->hasMethod('remember'))->toBeTrue()
            ->and($reflection->hasMethod('forget'))->toBeTrue()
            ->and($reflection->hasMethod('flush'))->toBeTrue();
    });

    it('defines SeoMetadataRepositoryInterface with findByModel, save, and delete methods', function (): void {
        $reflection = new ReflectionClass(SeoMetadataRepositoryInterface::class);

        expect($reflection->isInterface())->toBeTrue()
            ->and($reflection->hasMethod('findByModel'))->toBeTrue()
            ->and($reflection->hasMethod('save'))->toBeTrue()
            ->and($reflection->hasMethod('delete'))->toBeTrue();
    });

    it('defines SeoRouteRepositoryInterface with findByRouteName, save, and delete methods', function (): void {
        $reflection = new ReflectionClass(SeoRouteRepositoryInterface::class);

        expect($reflection->isInterface())->toBeTrue()
            ->and($reflection->hasMethod('findByRouteName'))->toBeTrue()
            ->and($reflection->hasMethod('save'))->toBeTrue()
            ->and($reflection->hasMethod('delete'))->toBeTrue();
    });

    it('defines SeoSettingsRepositoryInterface with get and update methods', function (): void {
        $reflection = new ReflectionClass(SeoSettingsRepositoryInterface::class);

        expect($reflection->isInterface())->toBeTrue()
            ->and($reflection->hasMethod('get'))->toBeTrue()
            ->and($reflection->hasMethod('update'))->toBeTrue();
    });
});
