<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Services\Resolvers;

use Mahdijd\SeoManagement\Contracts\SeoMetadataRepositoryInterface;
use Mahdijd\SeoManagement\DTOs\SeoContext;
use Mahdijd\SeoManagement\DTOs\SeoData;

/**
 * ModelResolver
 *
 * Second priority resolver in the resolution chain.
 * Loads model-attached SEO metadata from database via SeoMetadataRepositoryInterface.
 * Applies getSeoFallback() fallback values for any remaining null fields.
 * Does NOT access cache or generate HTML.
 */
class ModelResolver
{
    /**
     * Create a new ModelResolver instance.
     */
    public function __construct(
        protected SeoMetadataRepositoryInterface $repository,
    ) {}

    /**
     * Resolve SEO data for an Eloquent model.
     */
    public function resolve(SeoContext $context): SeoData
    {
        $model = $context->model;

        if ($model === null) {
            return new SeoData;
        }

        $record = $this->repository->findByModel($model);

        // Extract database metadata values (if record exists)
        $title = $record?->title;
        $description = $record?->description;
        $keywords = $record?->keywords;
        $canonical = $record?->canonical;
        $robots = $record?->robots;
        $ogTitle = $record?->og_title;
        $ogDescription = $record?->og_description;
        $ogImage = $record?->og_image;
        $ogType = $record?->og_type;
        $ogUrl = $record?->og_url;
        $ogSiteName = $record?->og_site_name;
        $twitterCard = $record?->twitter_card;
        $twitterTitle = $record?->twitter_title;
        $twitterDescription = $record?->twitter_description;
        $twitterImage = $record?->twitter_image;
        $jsonLd = $record?->json_ld;

        // Apply dynamic model fallbacks via getSeoFallback() if defined on the model
        if (method_exists($model, 'getSeoFallback')) {
            /** @var array<string, mixed> $fallback */
            $fallback = (array) $model->getSeoFallback();

            $title ??= is_string($fallback['title'] ?? null) ? $fallback['title'] : null;
            $description ??= is_string($fallback['description'] ?? null) ? $fallback['description'] : null;
            $keywords ??= is_string($fallback['keywords'] ?? null) ? $fallback['keywords'] : null;
            $canonical ??= is_string($fallback['canonical'] ?? null) ? $fallback['canonical'] : null;
            $robots ??= is_string($fallback['robots'] ?? null) ? $fallback['robots'] : null;
            $ogTitle ??= is_string($fallback['ogTitle'] ?? $fallback['og_title'] ?? null) ? ($fallback['ogTitle'] ?? $fallback['og_title']) : null;
            $ogDescription ??= is_string($fallback['ogDescription'] ?? $fallback['og_description'] ?? null) ? ($fallback['ogDescription'] ?? $fallback['og_description']) : null;
            $ogImage ??= is_string($fallback['ogImage'] ?? $fallback['og_image'] ?? null) ? ($fallback['ogImage'] ?? $fallback['og_image']) : null;
            $ogType ??= is_string($fallback['ogType'] ?? $fallback['og_type'] ?? null) ? ($fallback['ogType'] ?? $fallback['og_type']) : null;
            $ogUrl ??= is_string($fallback['ogUrl'] ?? $fallback['og_url'] ?? null) ? ($fallback['ogUrl'] ?? $fallback['og_url']) : null;
            $ogSiteName ??= is_string($fallback['ogSiteName'] ?? $fallback['og_site_name'] ?? null) ? ($fallback['ogSiteName'] ?? $fallback['og_site_name']) : null;
            $twitterCard ??= is_string($fallback['twitterCard'] ?? $fallback['twitter_card'] ?? null) ? ($fallback['twitterCard'] ?? $fallback['twitter_card']) : null;
            $twitterTitle ??= is_string($fallback['twitterTitle'] ?? $fallback['twitter_title'] ?? null) ? ($fallback['twitterTitle'] ?? $fallback['twitter_title']) : null;
            $twitterDescription ??= is_string($fallback['twitterDescription'] ?? $fallback['twitter_description'] ?? null) ? ($fallback['twitterDescription'] ?? $fallback['twitter_description']) : null;
            $twitterImage ??= is_string($fallback['twitterImage'] ?? $fallback['twitter_image'] ?? null) ? ($fallback['twitterImage'] ?? $fallback['twitter_image']) : null;
            $jsonLd ??= is_array($fallback['jsonLd'] ?? $fallback['json_ld'] ?? null) ? ($fallback['jsonLd'] ?? $fallback['json_ld']) : null;
        }

        return new SeoData(
            title: $title,
            description: $description,
            keywords: $keywords,
            canonical: $canonical,
            robots: $robots,
            ogTitle: $ogTitle,
            ogDescription: $ogDescription,
            ogImage: $ogImage,
            ogType: $ogType,
            ogUrl: $ogUrl,
            ogSiteName: $ogSiteName,
            twitterCard: $twitterCard,
            twitterTitle: $twitterTitle,
            twitterDescription: $twitterDescription,
            twitterImage: $twitterImage,
            jsonLd: $jsonLd,
        );
    }
}
