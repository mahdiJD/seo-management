<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Services\Resolvers;

use Mahdijd\SeoManagement\Contracts\SeoSettingsRepositoryInterface;
use Mahdijd\SeoManagement\DTOs\SeoContext;
use Mahdijd\SeoManagement\DTOs\SeoData;

/**
 * GlobalResolver
 *
 * Final (fourth) priority resolver in the resolution chain.
 * Loads global default fallback values from the database via SeoSettingsRepositoryInterface.
 * Does NOT access cache or generate HTML.
 */
class GlobalResolver
{
    /**
     * Create a new GlobalResolver instance.
     */
    public function __construct(
        protected SeoSettingsRepositoryInterface $repository,
    ) {}

    /**
     * Resolve global fallback SEO data.
     */
    public function resolve(SeoContext $context): SeoData
    {
        $settings = $this->repository->get();

        return new SeoData(
            title: $settings->default_title,
            description: $settings->default_description,
            canonical: $settings->default_canonical,
            robots: $settings->default_robots,
            ogImage: $settings->default_og_image,
            ogType: $settings->default_og_type,
            ogSiteName: $settings->default_og_site_name ?? $settings->site_name,
            twitterCard: $settings->default_twitter_card,
            twitterImage: $settings->default_twitter_image,
            jsonLd: $settings->default_json_ld,
        );
    }
}
