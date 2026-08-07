<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Services;

use Closure;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Mahdijd\SeoManagement\Contracts\SeoCacheManagerInterface;
use Mahdijd\SeoManagement\Models\SeoRoute;
use Mahdijd\SeoManagement\Models\SeoSettings;
use Throwable;

/**
 * SeoCacheManager
 *
 * Single point of truth for all SEO cache key generation, storage, retrieval, and invalidation.
 * Does NOT access database directly or render HTML tags.
 */
class SeoCacheManager implements SeoCacheManagerInterface
{
    /**
     * Cache tag used when cache driver supports tagging.
     */
    public const CACHE_TAG = 'seo';

    /**
     * Get an item from the cache, or execute the given Closure and store the result.
     *
     * @param  Closure(): string  $callback
     */
    public function remember(string $key, Closure $callback): string
    {
        // If caching is explicitly disabled in config, bypass cache completely
        if (! config('seo.cache', true)) {
            return $callback();
        }

        try {
            $ttl = (int) config('seo.cache_ttl', 86400);
            $store = $this->getCacheStore();

            if ($this->supportsTags($store)) {
                /** @var string */
                return $store->tags([self::CACHE_TAG])->remember($key, $ttl, $callback);
            }

            /** @var string */
            return $store->remember($key, $ttl, $callback);
        } catch (Throwable) {
            // Fail gracefully on cache failures (e.g. Redis offline) by executing callback
            return $callback();
        }
    }

    /**
     * Forget a specific cache key.
     */
    public function forget(string $key): void
    {
        try {
            $store = $this->getCacheStore();

            if ($this->supportsTags($store)) {
                $store->tags([self::CACHE_TAG])->forget($key);
            } else {
                $store->forget($key);
            }
        } catch (Throwable) {
            // Ignore cache invalidation failures silently
        }
    }

    /**
     * Flush all SEO cache entries.
     */
    public function flush(): void
    {
        try {
            $store = $this->getCacheStore();

            if ($this->supportsTags($store)) {
                $store->tags([self::CACHE_TAG])->flush();
            } else {
                // When tags are not supported, flush the store or clear known key space
                $store->flush();
            }
        } catch (Throwable) {
            // Ignore cache flush failures silently
        }
    }

    /**
     * Generate deterministic cache key for an Eloquent model.
     *
     * Format: seo:model:{class}:{id}:{updated_at_timestamp}
     */
    public function modelKey(Model $model): string
    {
        $updatedAt = $model->updated_at?->timestamp ?: 0;

        return sprintf(
            'seo:model:%s:%s:%d',
            str_replace('\\', '_', strtolower($model->getMorphClass())),
            $model->getKey(),
            $updatedAt
        );
    }

    /**
     * Generate deterministic cache key for a named route.
     *
     * Format: seo:route:{route_name}:{updated_at_timestamp}
     */
    public function routeKey(string|SeoRoute $route): string
    {
        if ($route instanceof SeoRoute) {
            $routeName = $route->route_name;
            $updatedAt = $route->updated_at->timestamp;
        } else {
            $routeName = $route;
            $updatedAt = 0;
        }

        return sprintf('seo:route:%s:%d', strtolower($routeName), $updatedAt);
    }

    /**
     * Generate deterministic cache key for global settings.
     *
     * Format: seo:settings:{updated_at_timestamp}
     */
    public function settingsKey(?SeoSettings $settings = null): string
    {
        $updatedAt = $settings?->updated_at?->timestamp ?: 0;

        return sprintf('seo:settings:%d', $updatedAt);
    }

    /**
     * Generate deterministic cache key for runtime cached output.
     *
     * Format: seo:runtime:{identifier}
     */
    public function runtimeKey(string $identifier): string
    {
        return sprintf('seo:runtime:%s', $identifier);
    }

    /**
     * Get the configured Laravel cache store repository instance.
     */
    protected function getCacheStore(): Repository
    {
        $storeName = config('seo.cache_store');

        return Cache::store(is_string($storeName) && $storeName !== '' ? $storeName : null);
    }

    /**
     * Determine if the given cache store supports tagging.
     */
    protected function supportsTags(Repository $store): bool
    {
        try {
            return method_exists($store->getStore(), 'tags');
        } catch (Throwable) {
            return false;
        }
    }
}
