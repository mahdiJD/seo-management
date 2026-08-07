<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Events;

/**
 * SeoCacheCleared
 *
 * Event dispatched after an SEO cache key or the entire SEO cache has been cleared.
 */
class SeoCacheCleared
{
    /**
     * Create a new SeoCacheCleared event instance.
     *
     * @param  string  $cacheKey  The cache key that was cleared or 'all' if flushed.
     */
    public function __construct(
        public readonly string $cacheKey,
    ) {}
}
