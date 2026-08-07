<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Services;

use Mahdijd\SeoManagement\Contracts\SeoRendererInterface;
use Mahdijd\SeoManagement\DTOs\SeoData;

/**
 * SeoRenderer
 *
 * Generates deterministic, XSS-safe HTML tags from a SeoData DTO.
 * Does NOT query database, access cache, or read configuration.
 */
class SeoRenderer implements SeoRendererInterface
{
    /**
     * Render the given SeoData object into HTML meta tags.
     */
    public function render(SeoData $data): string
    {
        $tags = [];

        // 1. Standard Title Tag
        if ($this->hasValue($data->title())) {
            $tags[] = sprintf('<title>%s</title>', $this->escape($data->title()));
        }

        // 2. Standard Meta Tags
        if ($this->hasValue($data->description())) {
            $tags[] = sprintf('<meta name="description" content="%s">', $this->escape($data->description()));
        }

        if ($this->hasValue($data->keywords())) {
            $tags[] = sprintf('<meta name="keywords" content="%s">', $this->escape($data->keywords()));
        }

        if ($this->hasValue($data->canonical())) {
            $tags[] = sprintf('<link rel="canonical" href="%s">', $this->escape($data->canonical()));
        }

        if ($this->hasValue($data->robots())) {
            $tags[] = sprintf('<meta name="robots" content="%s">', $this->escape($data->robots()));
        }

        // 3. Open Graph Tags
        if ($this->hasValue($data->ogTitle())) {
            $tags[] = sprintf('<meta property="og:title" content="%s">', $this->escape($data->ogTitle()));
        }

        if ($this->hasValue($data->ogDescription())) {
            $tags[] = sprintf('<meta property="og:description" content="%s">', $this->escape($data->ogDescription()));
        }

        if ($this->hasValue($data->ogImage())) {
            $tags[] = sprintf('<meta property="og:image" content="%s">', $this->escape($data->ogImage()));
        }

        if ($this->hasValue($data->ogType())) {
            $tags[] = sprintf('<meta property="og:type" content="%s">', $this->escape($data->ogType()));
        }

        if ($this->hasValue($data->ogUrl())) {
            $tags[] = sprintf('<meta property="og:url" content="%s">', $this->escape($data->ogUrl()));
        }

        if ($this->hasValue($data->ogSiteName())) {
            $tags[] = sprintf('<meta property="og:site_name" content="%s">', $this->escape($data->ogSiteName()));
        }

        // 4. Twitter Card Tags
        if ($this->hasValue($data->twitterCard())) {
            $tags[] = sprintf('<meta name="twitter:card" content="%s">', $this->escape($data->twitterCard()));
        }

        if ($this->hasValue($data->twitterTitle())) {
            $tags[] = sprintf('<meta name="twitter:title" content="%s">', $this->escape($data->twitterTitle()));
        }

        if ($this->hasValue($data->twitterDescription())) {
            $tags[] = sprintf('<meta name="twitter:description" content="%s">', $this->escape($data->twitterDescription()));
        }

        if ($this->hasValue($data->twitterImage())) {
            $tags[] = sprintf('<meta name="twitter:image" content="%s">', $this->escape($data->twitterImage()));
        }

        // 5. JSON-LD Structured Data
        if (is_array($data->jsonLd()) && ! empty($data->jsonLd())) {
            $json = json_encode(
                $data->jsonLd(),
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );

            if ($json !== false) {
                $tags[] = sprintf("<script type=\"application/ld+json\">\n%s\n</script>", $json);
            }
        }

        return implode("\n", $tags);
    }

    /**
     * Check if a string property has a non-null, non-empty value.
     */
    protected function hasValue(?string $value): bool
    {
        return $value !== null && $value !== '';
    }

    /**
     * Safely escape an HTML string attribute value to prevent XSS.
     */
    protected function escape(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
