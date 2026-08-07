<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Mahdijd\SeoManagement\Contracts\SeoCacheManagerInterface;
use Mahdijd\SeoManagement\Contracts\SeoRendererInterface;
use Mahdijd\SeoManagement\Contracts\SeoResolverInterface;
use Mahdijd\SeoManagement\DTOs\SeoContext;
use Mahdijd\SeoManagement\DTOs\SeoData;
use Mahdijd\SeoManagement\Events\SeoRendered;
use Mahdijd\SeoManagement\Events\SeoResolved;
use Throwable;

/**
 * SeoManager
 *
 * Central service and main entry point orchestrating metadata resolution, HTML rendering,
 * cache retrieval, and event dispatching.
 *
 * Fails gracefully on any exception to prevent breaking host application page rendering.
 */
class SeoManager
{
    /**
     * Create a new SeoManager instance.
     */
    public function __construct(
        protected SeoResolverInterface $resolver,
        protected SeoRendererInterface $renderer,
        protected SeoCacheManagerInterface $cacheManager,
    ) {}

    /**
     * Resolve SeoData for an Eloquent model with optional runtime overrides.
     * Alias for resolveModel().
     *
     * @param  array<string, mixed>  $overrides
     */
    public function forModel(Model $model, array $overrides = []): SeoData
    {
        return $this->resolveModel($model, $overrides);
    }

    /**
     * Resolve SeoData for a named route with optional runtime overrides.
     * Alias for resolveRoute().
     *
     * @param  array<string, mixed>  $overrides
     */
    public function forRoute(string $routeName, array $overrides = []): SeoData
    {
        return $this->resolveRoute($routeName, $overrides);
    }

    /**
     * Resolve SeoData for an Eloquent model with optional runtime overrides.
     *
     * @param  array<string, mixed>  $overrides
     */
    public function resolveModel(Model $model, array $overrides = []): SeoData
    {
        $context = SeoContext::forModel($model, $overrides);
        $data = $this->resolver->resolve($context);

        Event::dispatch(new SeoResolved($data, $context));

        return $data;
    }

    /**
     * Resolve SeoData for a named route with optional runtime overrides.
     *
     * @param  array<string, mixed>  $overrides
     */
    public function resolveRoute(string $routeName, array $overrides = []): SeoData
    {
        $context = SeoContext::forRoute($routeName, $overrides);
        $data = $this->resolver->resolve($context);

        Event::dispatch(new SeoResolved($data, $context));

        return $data;
    }

    /**
     * Resolve SeoData from explicit runtime overrides array.
     *
     * @param  array<string, mixed>  $runtime
     */
    public function resolve(array $runtime): SeoData
    {
        $context = SeoContext::forRuntime($runtime);
        $data = $this->resolver->resolve($context);

        Event::dispatch(new SeoResolved($data, $context));

        return $data;
    }

    /**
     * Render a SeoData DTO into HTML tags.
     */
    public function render(SeoData $data, ?SeoContext $context = null): string
    {
        try {
            $html = $this->renderer->render($data);
            $context ??= new SeoContext;

            Event::dispatch(new SeoRendered($html, $context));

            return $html;
        } catch (Throwable) {
            return '';
        }
    }

    /**
     * Resolve and render HTML meta tags for an Eloquent model, utilizing caching.
     *
     * @param  array<string, mixed>  $overrides
     */
    public function renderForModel(Model $model, array $overrides = []): string
    {
        try {
            $context = SeoContext::forModel($model, $overrides);
            $cacheKey = $this->cacheManager->modelKey($model);

            // If runtime overrides exist, append a hash to the cache key to avoid cache pollution
            if (! empty($overrides)) {
                $cacheKey .= ':override:'.md5((string) json_encode($overrides));
            }

            return $this->cacheManager->remember($cacheKey, function () use ($context): string {
                $data = $this->resolver->resolve($context);
                Event::dispatch(new SeoResolved($data, $context));

                $html = $this->renderer->render($data);
                Event::dispatch(new SeoRendered($html, $context));

                return $html;
            });
        } catch (Throwable) {
            return '';
        }
    }

    /**
     * Resolve and render HTML meta tags for a named web route, utilizing caching.
     *
     * @param  array<string, mixed>  $overrides
     */
    public function renderForRoute(string $routeName, array $overrides = []): string
    {
        try {
            $context = SeoContext::forRoute($routeName, $overrides);
            $cacheKey = $this->cacheManager->routeKey($routeName);

            if (! empty($overrides)) {
                $cacheKey .= ':override:'.md5((string) json_encode($overrides));
            }

            return $this->cacheManager->remember($cacheKey, function () use ($context): string {
                $data = $this->resolver->resolve($context);
                Event::dispatch(new SeoResolved($data, $context));

                $html = $this->renderer->render($data);
                Event::dispatch(new SeoRendered($html, $context));

                return $html;
            });
        } catch (Throwable) {
            return '';
        }
    }

    /**
     * Clear cached SEO data for a specific Eloquent model.
     */
    public function clearModel(Model $model): void
    {
        $this->cacheManager->forget($this->cacheManager->modelKey($model));
    }

    /**
     * Clear cached SEO data for a specific named web route.
     */
    public function clearRoute(string $routeName): void
    {
        $this->cacheManager->forget($this->cacheManager->routeKey($routeName));
    }

    /**
     * Flush all SEO cache entries.
     */
    public function flushAll(): void
    {
        $this->cacheManager->flush();
    }

    /**
     * Access the cache manager instance directly.
     */
    public function getCacheManager(): SeoCacheManagerInterface
    {
        return $this->cacheManager;
    }

    /**
     * Access the resolver instance directly.
     */
    public function getResolver(): SeoResolverInterface
    {
        return $this->resolver;
    }

    /**
     * Access the renderer instance directly.
     */
    public function getRenderer(): SeoRendererInterface
    {
        return $this->renderer;
    }
}
