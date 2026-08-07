<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Contracts;

use Mahdijd\SeoManagement\Models\SeoSettings;

/**
 * SeoSettingsRepositoryInterface
 *
 * Contract for repositories managing database access for global fallback SeoSettings.
 */
interface SeoSettingsRepositoryInterface
{
    /**
     * Get the single global SeoSettings record (auto-creating if none exists).
     */
    public function get(): SeoSettings;

    /**
     * Update global fallback SEO settings.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(array $data): SeoSettings;
}
