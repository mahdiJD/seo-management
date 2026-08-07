<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Mahdijd\SeoManagement\DTOs\SeoData;
use Mahdijd\SeoManagement\Services\SeoManager;

/**
 * Seo Facade
 *
 * Provides a static interface to the SeoManager singleton.
 *
 * @method static SeoData forModel(Model $model, array<string, mixed> $overrides = [])
 * @method static SeoData forRoute(string $routeName, array<string, mixed> $overrides = [])
 * @method static SeoData resolveModel(Model $model, array<string, mixed> $overrides = [])
 * @method static SeoData resolveRoute(string $routeName, array<string, mixed> $overrides = [])
 * @method static SeoData resolve(array<string, mixed> $runtime)
 * @method static string render(SeoData $data)
 * @method static string renderForModel(Model $model, array<string, mixed> $overrides = [])
 * @method static string renderForRoute(string $routeName, array<string, mixed> $overrides = [])
 * @method static void clearModel(Model $model)
 * @method static void clearRoute(string $routeName)
 * @method static void flushAll()
 *
 * @see SeoManager
 */
class Seo extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return SeoManager::class;
    }
}
