<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Services;

use Mahdijd\SeoManagement\Contracts\SeoResolverInterface;
use Mahdijd\SeoManagement\DTOs\SeoContext;
use Mahdijd\SeoManagement\DTOs\SeoData;
use Mahdijd\SeoManagement\Services\Resolvers\GlobalResolver;
use Mahdijd\SeoManagement\Services\Resolvers\ModelResolver;
use Mahdijd\SeoManagement\Services\Resolvers\RouteResolver;
use Mahdijd\SeoManagement\Services\Resolvers\RuntimeResolver;

/**
 * SeoResolver
 *
 * Orchestrates the resolver chain in strict priority order:
 * 1. Explicit runtime overrides (RuntimeResolver)
 * 2. Model-attached SEO metadata & fallbacks (ModelResolver)
 * 3. Named web route SEO metadata (RouteResolver)
 * 4. Application global fallback settings (GlobalResolver)
 *
 * Merges values so higher-priority resolvers are NEVER overwritten by lower-priority ones.
 */
class SeoResolver implements SeoResolverInterface
{
    /**
     * Create a new SeoResolver instance.
     *
     * @param  RuntimeResolver  $runtimeResolver
     * @param  ModelResolver  $modelResolver
     * @param  RouteResolver  $routeResolver
     * @param  GlobalResolver  $globalResolver
     */
    public function __construct(
        protected RuntimeResolver $runtimeResolver,
        protected ModelResolver $modelResolver,
        protected RouteResolver $routeResolver,
        protected GlobalResolver $globalResolver,
    ) {
    }

    /**
     * Resolve SEO data for the given context using the priority chain.
     *
     * @param  SeoContext  $context
     * @return SeoData
     */
    public function resolve(SeoContext $context): SeoData
    {
        $runtime = $this->runtimeResolver->resolve($context);
        $model   = $this->modelResolver->resolve($context);
        $route   = $this->routeResolver->resolve($context);
        $global  = $this->globalResolver->resolve($context);

        return new SeoData(
            title: $runtime->title ?? $model->title ?? $route->title ?? $global->title,
            description: $runtime->description ?? $model->description ?? $route->description ?? $global->description,
            keywords: $runtime->keywords ?? $model->keywords ?? $route->keywords ?? $global->keywords,
            canonical: $runtime->canonical ?? $model->canonical ?? $route->canonical ?? $global->canonical,
            robots: $runtime->robots ?? $model->robots ?? $route->robots ?? $global->robots,
            ogTitle: $runtime->ogTitle ?? $model->ogTitle ?? $route->ogTitle ?? $global->ogTitle,
            ogDescription: $runtime->ogDescription ?? $model->ogDescription ?? $route->ogDescription ?? $global->ogDescription,
            ogImage: $runtime->ogImage ?? $model->ogImage ?? $route->ogImage ?? $global->ogImage,
            ogType: $runtime->ogType ?? $model->ogType ?? $route->ogType ?? $global->ogType,
            ogUrl: $runtime->ogUrl ?? $model->ogUrl ?? $route->ogUrl ?? $global->ogUrl,
            ogSiteName: $runtime->ogSiteName ?? $model->ogSiteName ?? $route->ogSiteName ?? $global->ogSiteName,
            twitterCard: $runtime->twitterCard ?? $model->twitterCard ?? $route->twitterCard ?? $global->twitterCard,
            twitterTitle: $runtime->twitterTitle ?? $model->twitterTitle ?? $route->twitterTitle ?? $global->twitterTitle,
            twitterDescription: $runtime->twitterDescription ?? $model->twitterDescription ?? $route->twitterDescription ?? $global->twitterDescription,
            twitterImage: $runtime->twitterImage ?? $model->twitterImage ?? $route->twitterImage ?? $global->twitterImage,
            jsonLd: $runtime->jsonLd ?? $model->jsonLd ?? $route->jsonLd ?? $global->jsonLd,
        );
    }
}
