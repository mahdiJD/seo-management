<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Repositories;

use Mahdijd\SeoManagement\Contracts\SeoRouteRepositoryInterface;
use Mahdijd\SeoManagement\Models\SeoRoute;

/**
 * SeoRouteRepository
 *
 * Eloquent-based repository managing database access for named web route SeoRoute records.
 * Encapsulates all Eloquent queries for seo_routes. No caching or business logic.
 */
class SeoRouteRepository implements SeoRouteRepositoryInterface
{
    /**
     * Find the SeoRoute record for a given route name.
     */
    public function findByRouteName(string $routeName): ?SeoRoute
    {
        return SeoRoute::query()
            ->where('route_name', $routeName)
            ->first();
    }

    /**
     * Save or update SEO metadata for a named route.
     *
     * @param  array<string, mixed>  $data
     */
    public function save(string $routeName, array $data): SeoRoute
    {
        return SeoRoute::query()->updateOrCreate(
            [
                'route_name' => $routeName,
            ],
            $data
        );
    }

    /**
     * Delete the SeoRoute record for a given route name.
     */
    public function delete(string $routeName): bool
    {
        $record = $this->findByRouteName($routeName);

        if (! $record) {
            return false;
        }

        return (bool) $record->delete();
    }
}
