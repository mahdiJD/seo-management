<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mahdijd\SeoManagement\Contracts\SeoRouteRepositoryInterface;
use Mahdijd\SeoManagement\DTOs\SeoContext;
use Mahdijd\SeoManagement\Models\SeoRoute;
use Mahdijd\SeoManagement\Services\Resolvers\RouteResolver;

uses(RefreshDatabase::class);

describe('RouteResolver', function (): void {
    beforeEach(function (): void {
        $this->repository = app(SeoRouteRepositoryInterface::class);
        $this->resolver   = new RouteResolver($this->repository);
    });

    it('returns empty SeoData when routeName is null or empty', function (): void {
        $nullContext  = new SeoContext(routeName: null);
        $emptyContext = new SeoContext(routeName: '');

        expect($this->resolver->resolve($nullContext)->title)->toBeNull()
            ->and($this->resolver->resolve($emptyContext)->title)->toBeNull();
    });

    it('returns empty SeoData when no route record exists for routeName', function (): void {
        $context = SeoContext::forRoute('non-existent-route');

        $result = $this->resolver->resolve($context);

        expect($result->title)->toBeNull()
            ->and($result->description)->toBeNull();
    });

    it('resolves route SEO metadata from database when record exists', function (): void {
        SeoRoute::create([
            'route_name'  => 'about',
            'title'       => 'About Us Title',
            'description' => 'About Us Description',
            'og_title'    => 'About Us OG Title',
        ]);

        $context = SeoContext::forRoute('about');

        $result = $this->resolver->resolve($context);

        expect($result->title)->toBe('About Us Title')
            ->and($result->description)->toBe('About Us Description')
            ->and($result->ogTitle)->toBe('About Us OG Title');
    });
});
