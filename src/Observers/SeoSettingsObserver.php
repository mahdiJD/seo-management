<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Observers;

use Illuminate\Support\Facades\Event;
use Mahdijd\SeoManagement\Contracts\SeoCacheManagerInterface;
use Mahdijd\SeoManagement\Events\SeoCacheCleared;
use Mahdijd\SeoManagement\Models\SeoSettings;

/**
 * SeoSettingsObserver
 *
 * Automatically flushes ALL SEO cache whenever the global SeoSettings database record
 * is saved, updated, or deleted.
 */
class SeoSettingsObserver
{
    /**
     * Create a new SeoSettingsObserver instance.
     *
     * @param  SeoCacheManagerInterface  $cacheManager
     */
    public function __construct(
        protected SeoCacheManagerInterface $cacheManager,
    ) {
    }

    /**
     * Handle the SeoSettings "saved" event.
     *
     * @param  SeoSettings  $settings
     */
    public function saved(SeoSettings $settings): void
    {
        $this->flushCache();
    }

    /**
     * Handle the SeoSettings "deleted" event.
     *
     * @param  SeoSettings  $settings
     */
    public function deleted(SeoSettings $settings): void
    {
        $this->flushCache();
    }

    /**
     * Flush all SEO cache.
     */
    protected function flushCache(): void
    {
        $this->cacheManager->flush();

        Event::dispatch(new SeoCacheCleared('all'));
    }
}
