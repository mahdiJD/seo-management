<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\DTOs;

use Illuminate\Database\Eloquent\Model;

/**
 * SeoContext
 *
 * Immutable value object carrying resolution parameters passed to the resolver chain.
 *
 * Must NOT resolve SEO data, render HTML, or access the database.
 */
final readonly class SeoContext
{
    /**
     * Create a new immutable SeoContext instance.
     *
     * @param  Model|null  $model  Eloquent model associated with the request (if any)
     * @param  string|null  $routeName  Laravel named route (if any)
     * @param  array<string, mixed>  $runtimeOverrides  Explicit runtime values (top priority)
     * @param  string|null  $requestUrl  Current URL for canonical calculations
     * @param  string|null  $cacheKey  Identifier for runtime caching
     */
    public function __construct(
        public ?Model $model = null,
        public ?string $routeName = null,
        public array $runtimeOverrides = [],
        public ?string $requestUrl = null,
        public ?string $cacheKey = null,
    ) {}

    /**
     * Create a context instance for an Eloquent model with optional runtime overrides.
     *
     * @param  array<string, mixed>  $overrides
     */
    public static function forModel(Model $model, array $overrides = [], ?string $requestUrl = null): self
    {
        return new self(
            model: $model,
            runtimeOverrides: $overrides,
            requestUrl: $requestUrl,
        );
    }

    /**
     * Create a context instance for a named route with optional runtime overrides.
     *
     * @param  array<string, mixed>  $overrides
     */
    public static function forRoute(string $routeName, array $overrides = [], ?string $requestUrl = null): self
    {
        return new self(
            routeName: $routeName,
            runtimeOverrides: $overrides,
            requestUrl: $requestUrl,
        );
    }

    /**
     * Create a context instance for explicit runtime overrides.
     *
     * @param  array<string, mixed>  $overrides
     */
    public static function forRuntime(array $overrides = [], ?string $cacheKey = null, ?string $requestUrl = null): self
    {
        return new self(
            runtimeOverrides: $overrides,
            requestUrl: $requestUrl,
            cacheKey: $cacheKey,
        );
    }
}
