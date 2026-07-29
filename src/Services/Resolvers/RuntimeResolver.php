<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Services\Resolvers;

use Mahdijd\SeoManagement\DTOs\SeoContext;
use Mahdijd\SeoManagement\DTOs\SeoData;

/**
 * RuntimeResolver
 *
 * Highest priority resolver in the resolution chain.
 * Reads runtime override values from SeoContext::$runtimeOverrides and maps them into a SeoData DTO.
 * Does NOT access database, cache, or configuration.
 */
class RuntimeResolver
{
    /**
     * Resolve SEO data from runtime overrides.
     *
     * @param  SeoContext  $context
     * @return SeoData
     */
    public function resolve(SeoContext $context): SeoData
    {
        $overrides = $context->runtimeOverrides;

        if (empty($overrides)) {
            return new SeoData();
        }

        return new SeoData(
            title: is_string($overrides['title'] ?? null) ? $overrides['title'] : null,
            description: is_string($overrides['description'] ?? null) ? $overrides['description'] : null,
            keywords: is_string($overrides['keywords'] ?? null) ? $overrides['keywords'] : null,
            canonical: is_string($overrides['canonical'] ?? null) ? $overrides['canonical'] : null,
            robots: is_string($overrides['robots'] ?? null) ? $overrides['robots'] : null,
            ogTitle: is_string($overrides['ogTitle'] ?? null) ? $overrides['ogTitle'] : null,
            ogDescription: is_string($overrides['ogDescription'] ?? null) ? $overrides['ogDescription'] : null,
            ogImage: is_string($overrides['ogImage'] ?? null) ? $overrides['ogImage'] : null,
            ogType: is_string($overrides['ogType'] ?? null) ? $overrides['ogType'] : null,
            ogUrl: is_string($overrides['ogUrl'] ?? null) ? $overrides['ogUrl'] : null,
            ogSiteName: is_string($overrides['ogSiteName'] ?? null) ? $overrides['ogSiteName'] : null,
            twitterCard: is_string($overrides['twitterCard'] ?? null) ? $overrides['twitterCard'] : null,
            twitterTitle: is_string($overrides['twitterTitle'] ?? null) ? $overrides['twitterTitle'] : null,
            twitterDescription: is_string($overrides['twitterDescription'] ?? null) ? $overrides['twitterDescription'] : null,
            twitterImage: is_string($overrides['twitterImage'] ?? null) ? $overrides['twitterImage'] : null,
            jsonLd: is_array($overrides['jsonLd'] ?? null) ? $overrides['jsonLd'] : null,
        );
    }
}
