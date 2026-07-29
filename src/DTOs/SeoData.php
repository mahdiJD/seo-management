<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\DTOs;

/**
 * SeoData
 *
 * Immutable Data Transfer Object carrying resolved SEO metadata between package layers.
 *
 * All properties are public readonly with corresponding getter methods for API convenience.
 */
final readonly class SeoData
{
    /**
     * Create a new immutable SeoData instance.
     *
     * @param  array<string, mixed>|null  $jsonLd
     */
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $keywords = null,
        public ?string $canonical = null,
        public ?string $robots = null,
        public ?string $ogTitle = null,
        public ?string $ogDescription = null,
        public ?string $ogImage = null,
        public ?string $ogType = null,
        public ?string $ogUrl = null,
        public ?string $ogSiteName = null,
        public ?string $twitterCard = null,
        public ?string $twitterTitle = null,
        public ?string $twitterDescription = null,
        public ?string $twitterImage = null,
        public ?array $jsonLd = null,
    ) {
    }

    /**
     * Get the SEO title.
     */
    public function title(): ?string
    {
        return $this->title;
    }

    /**
     * Get the meta description.
     */
    public function description(): ?string
    {
        return $this->description;
    }

    /**
     * Get meta keywords.
     */
    public function keywords(): ?string
    {
        return $this->keywords;
    }

    /**
     * Get the canonical URL.
     */
    public function canonical(): ?string
    {
        return $this->canonical;
    }

    /**
     * Get the robots directive.
     */
    public function robots(): ?string
    {
        return $this->robots;
    }

    /**
     * Get the Open Graph title.
     */
    public function ogTitle(): ?string
    {
        return $this->ogTitle;
    }

    /**
     * Get the Open Graph description.
     */
    public function ogDescription(): ?string
    {
        return $this->ogDescription;
    }

    /**
     * Get the Open Graph image URL.
     */
    public function ogImage(): ?string
    {
        return $this->ogImage;
    }

    /**
     * Get the Open Graph type.
     */
    public function ogType(): ?string
    {
        return $this->ogType;
    }

    /**
     * Get the Open Graph URL.
     */
    public function ogUrl(): ?string
    {
        return $this->ogUrl;
    }

    /**
     * Get the Open Graph site name.
     */
    public function ogSiteName(): ?string
    {
        return $this->ogSiteName;
    }

    /**
     * Get the Twitter card type.
     */
    public function twitterCard(): ?string
    {
        return $this->twitterCard;
    }

    /**
     * Get the Twitter card title.
     */
    public function twitterTitle(): ?string
    {
        return $this->twitterTitle;
    }

    /**
     * Get the Twitter card description.
     */
    public function twitterDescription(): ?string
    {
        return $this->twitterDescription;
    }

    /**
     * Get the Twitter card image URL.
     */
    public function twitterImage(): ?string
    {
        return $this->twitterImage;
    }

    /**
     * Get the JSON-LD structured data array.
     *
     * @return array<string, mixed>|null
     */
    public function jsonLd(): ?array
    {
        return $this->jsonLd;
    }

    /**
     * Convert the DTO to an associative array using camelCase keys.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'title'              => $this->title,
            'description'        => $this->description,
            'keywords'           => $this->keywords,
            'canonical'          => $this->canonical,
            'robots'             => $this->robots,
            'ogTitle'            => $this->ogTitle,
            'ogDescription'      => $this->ogDescription,
            'ogImage'            => $this->ogImage,
            'ogType'             => $this->ogType,
            'ogUrl'              => $this->ogUrl,
            'ogSiteName'         => $this->ogSiteName,
            'twitterCard'        => $this->twitterCard,
            'twitterTitle'       => $this->twitterTitle,
            'twitterDescription' => $this->twitterDescription,
            'twitterImage'       => $this->twitterImage,
            'jsonLd'             => $this->jsonLd,
        ];
    }
}
