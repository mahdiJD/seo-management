<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Repositories;

use Mahdijd\SeoManagement\Contracts\SeoSettingsRepositoryInterface;
use Mahdijd\SeoManagement\Models\SeoSettings;

/**
 * SeoSettingsRepository
 *
 * Repository managing database access for global fallback SeoSettings.
 * Guarantees a single record always exists via firstOrCreate([]).
 */
class SeoSettingsRepository implements SeoSettingsRepositoryInterface
{
    /**
     * Get the single global SeoSettings record (auto-creating if none exists).
     */
    public function get(): SeoSettings
    {
        return SeoSettings::firstOrCreate([]);
    }

    /**
     * Update global fallback SEO settings.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(array $data): SeoSettings
    {
        $settings = $this->get();
        $settings->update($data);

        return $settings->refresh();
    }
}
