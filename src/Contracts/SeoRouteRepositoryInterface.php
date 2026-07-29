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
     *
     * @param  string  $routeName
     * @return SeoRoute|null
     */
    public function findByRouteName(string $routeName): ?SeoRoute;

    /**
     * Save or update SEO metadata for a named route.
     *
     * @param  string  $routeName
     * @param  array<string, mixed>  $data
     * @return SeoRoute
     */
    public function save(string $routeName, array $data): SeoRoute;

    /**
     * Delete the SeoRoute record for a given route name.
     *
     * @param  string  $routeName
     * @return bool
     */
    public function delete(string $routeName): bool;
}
