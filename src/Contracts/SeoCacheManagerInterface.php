<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Contracts;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Mahdijd\SeoManagement\Models\SeoRoute;
use Mahdijd\SeoManagement\Models\SeoSettings;

/**
 * SeoCacheManagerInterface
 *
 * Contract for services that manage caching and invalidation of rendered SEO output.
 */
interface SeoCacheManagerInterface
{
    /**
     * Get an item from the cache, or execute the given Closure and store the result.
     *
     * @param  Closure(): string  $callback
     */
    public function remember(string $key, Closure $callback): string;

    /**
     * Forget a specific cache key.
     */
    public function forget(string $key): void;

    /**
     * Flush all SEO cache entries.
     */
    public function flush(): void;

    /**
     * Generate deterministic cache key for an Eloquent model.
     */
    public function modelKey(Model $model): string;

    /**
     * Generate deterministic cache key for a named route.
     */
    public function routeKey(string|SeoRoute $route): string;

    /**
     * Generate deterministic cache key for global settings.
     */
    public function settingsKey(?SeoSettings $settings = null): string;

    /**
     * Generate deterministic cache key for runtime cached output.
     */
    public function runtimeKey(string $identifier): string;
}
