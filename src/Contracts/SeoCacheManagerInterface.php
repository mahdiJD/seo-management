<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Contracts;

use Closure;

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
     * @param  string  $key
     * @param  Closure(): string  $callback
     * @return string
     */
    public function remember(string $key, Closure $callback): string;

    /**
     * Forget a specific cache key.
     *
     * @param  string  $key
     * @return void
     */
    public function forget(string $key): void;

    /**
     * Flush all SEO cache entries.
     *
     * @return void
     */
    public function flush(): void;
}
