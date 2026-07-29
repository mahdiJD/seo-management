<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Mahdijd\SeoManagement\Contracts\SeoCacheManagerInterface;
use Mahdijd\SeoManagement\Events\SeoCacheCleared;

/**
 * SeoModelObserver
 *
 * Generic observer attached to any Eloquent model using the HasSeo trait.
 * Automatically clears the model's SEO cache whenever the model is saved or deleted.
 */
class SeoModelObserver
{
    /**
     * Handle the model "saved" event.
     *
     * @param  Model  $model
     */
    public function saved(Model $model): void
    {
        $this->clearCache($model);
    }

    /**
     * Handle the model "deleted" event.
     *
     * @param  Model  $model
     */
    public function deleted(Model $model): void
    {
        $this->clearCache($model);
    }

    /**
     * Clear the cache for the model.
     *
     * @param  Model  $model
     */
    protected function clearCache(Model $model): void
    {
        /** @var SeoCacheManagerInterface $cacheManager */
        $cacheManager = app(SeoCacheManagerInterface::class);

        $key = $cacheManager->modelKey($model);
        $cacheManager->forget($key);

        Event::dispatch(new SeoCacheCleared($key));
    }
}
