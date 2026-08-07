<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Contracts;

use Mahdijd\SeoManagement\Models\SeoRoute;

/**
 * SeoRouteRepositoryInterface
 *
 * Contract for repositories managing database access for named web route SeoRoute records.
 */
interface SeoRouteRepositoryInterface
{
    /**
     * Find the SeoRoute record for a given route name.
     */
    public function findByRouteName(string $routeName): ?SeoRoute;

    /**
     * Save or update SEO metadata for a named route.
     *
     * @param  array<string, mixed>  $data
     */
    public function save(string $routeName, array $data): SeoRoute;

    /**
     * Delete the SeoRoute record for a given route name.
     */
    public function delete(string $routeName): bool;
}
