<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Mahdijd\SeoManagement\Models\SeoMetadata;
use Mahdijd\SeoManagement\Observers\SeoModelObserver;

/**
 * HasSeo
 *
 * Trait providing Eloquent models with SEO metadata capabilities:
 * - Morphic one-to-one relationship (seo())
 * - Fallback metadata provider method (getSeoFallback())
 * - Automatic observer registration for cache invalidation (bootHasSeo())
 *
 * Must NOT render HTML tags or access cache directly.
 */
trait HasSeo
{
    /**
     * Boot the trait and register the SeoModelObserver for cache invalidation.
     */
    public static function bootHasSeo(): void
    {
        static::observe(SeoModelObserver::class);
    }

    /**
     * Get the polymorphic SEO metadata record associated with this model.
     *
     * @return MorphOne<SeoMetadata, $this>
     */
    public function seo(): MorphOne
    {
        /** @var Model $this */
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    /**
     * Get dynamic fallback values for SEO metadata fields.
     *
     * Developers may override this method on their Eloquent models to provide
     * automatic fallback values derived from model attributes (e.g. title, excerpt).
     *
     * Example:
     * ```php
     * public function getSeoFallback(): array
     * {
     *     return [
     *         'title'       => $this->title,
     *         'description' => $this->excerpt,
     *     ];
     * }
     * ```
     *
     * @return array<string, mixed>
     */
    public function getSeoFallback(): array
    {
        return [];
    }
}
