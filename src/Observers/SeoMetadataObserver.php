<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Observers;

use Illuminate\Support\Facades\Event;
use Mahdijd\SeoManagement\Contracts\SeoCacheManagerInterface;
use Mahdijd\SeoManagement\Events\SeoCacheCleared;
use Mahdijd\SeoManagement\Models\SeoMetadata;

/**
 * SeoMetadataObserver
 *
 * Automatically invalidates SEO cache for the parent Eloquent model
 * whenever its SeoMetadata database record is saved, updated, or deleted.
 */
class SeoMetadataObserver
{
    /**
     * Create a new SeoMetadataObserver instance.
     *
     * @param  SeoCacheManagerInterface  $cacheManager
     */
    public function __construct(
        protected SeoCacheManagerInterface $cacheManager,
    ) {
    }

    /**
     * Handle the SeoMetadata "saved" event.
     *
     * @param  SeoMetadata  $metadata
     */
    public function saved(SeoMetadata $metadata): void
    {
        $this->clearCache($metadata);
    }

    /**
     * Handle the SeoMetadata "deleted" event.
     *
     * @param  SeoMetadata  $metadata
     */
    public function deleted(SeoMetadata $metadata): void
    {
        $this->clearCache($metadata);
    }

    /**
     * Clear the cache for the associated model.
     *
     * @param  SeoMetadata  $metadata
     */
    protected function clearCache(SeoMetadata $metadata): void
    {
        $seoable = $metadata->seoable;

        if ($seoable !== null) {
            $key = $this->cacheManager->modelKey($seoable);
            $this->cacheManager->forget($key);

            Event::dispatch(new SeoCacheCleared($key));
        }
    }
}
