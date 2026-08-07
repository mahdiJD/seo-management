<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Services\Resolvers;

use Mahdijd\SeoManagement\Contracts\SeoRouteRepositoryInterface;
use Mahdijd\SeoManagement\DTOs\SeoContext;
use Mahdijd\SeoManagement\DTOs\SeoData;

/**
 * RouteResolver
 *
 * Third priority resolver in the resolution chain.
 * Loads named web route SEO metadata from database via SeoRouteRepositoryInterface.
 * Does NOT access cache or generate HTML.
 */
class RouteResolver
{
    /**
     * Create a new RouteResolver instance.
     */
    public function __construct(
        protected SeoRouteRepositoryInterface $repository,
    ) {}

    /**
     * Resolve SEO data for a named route.
     */
    public function resolve(SeoContext $context): SeoData
    {
        $routeName = $context->routeName;

        if ($routeName === null || $routeName === '') {
            return new SeoData;
        }

        $record = $this->repository->findByRouteName($routeName);

        if ($record === null) {
            return new SeoData;
        }

        return new SeoData(
            title: $record->title,
            description: $record->description,
            keywords: $record->keywords,
            canonical: $record->canonical,
            robots: $record->robots,
            ogTitle: $record->og_title,
            ogDescription: $record->og_description,
            ogImage: $record->og_image,
            ogType: $record->og_type,
            ogUrl: $record->og_url,
            ogSiteName: $record->og_site_name,
            twitterCard: $record->twitter_card,
            twitterTitle: $record->twitter_title,
            twitterDescription: $record->twitter_description,
            twitterImage: $record->twitter_image,
            jsonLd: $record->json_ld,
        );
    }
}
