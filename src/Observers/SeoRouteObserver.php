<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Observers;

use Illuminate\Support\Facades\Event;
use Mahdijd\SeoManagement\Contracts\SeoCacheManagerInterface;
use Mahdijd\SeoManagement\Events\SeoCacheCleared;
use Mahdijd\SeoManagement\Models\SeoRoute;

/**
 * SeoRouteObserver
 *
 * Automatically invalidates SEO cache for a named web route
 * whenever its SeoRoute database record is saved, updated, or deleted.
 */
class SeoRouteObserver
{
    /**
     * Create a new SeoRouteObserver instance.
     *
     * @param  SeoCacheManagerInterface  $cacheManager
     */
    public function __construct(
        protected SeoCacheManagerInterface $cacheManager,
    ) {
    }

    /**
     * Handle the SeoRoute "saved" event.
     *
     * @param  SeoRoute  $route
     */
    public function saved(SeoRoute $route): void
    {
        $this->clearCache($route);
    }

    /**
     * Handle the SeoRoute "deleted" event.
     *
     * @param  SeoRoute  $route
     */
    public function deleted(SeoRoute $route): void
    {
        $this->clearCache($route);
    }

    /**
     * Clear the cache for the associated route.
     *
     * @param  SeoRoute  $route
     */
    protected function clearCache(SeoRoute $route): void
    {
        $key = $this->cacheManager->routeKey($route);
        $this->cacheManager->forget($key);

        Event::dispatch(new SeoCacheCleared($key));
    }
}
