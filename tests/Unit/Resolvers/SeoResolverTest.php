<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mahdijd\SeoManagement\Contracts\SeoResolverInterface;
use Mahdijd\SeoManagement\Contracts\SeoSettingsRepositoryInterface;
use Mahdijd\SeoManagement\DTOs\SeoContext;
use Mahdijd\SeoManagement\Models\SeoMetadata;
use Mahdijd\SeoManagement\Models\SeoRoute;
use Mahdijd\SeoManagement\Tests\Fixtures\TestPost;

uses(RefreshDatabase::class);

describe('SeoResolver Priority Chain', function (): void {
    beforeEach(function (): void {
        $this->seoResolver = app(SeoResolverInterface::class);

        // Setup global settings
        app(SeoSettingsRepositoryInterface::class)->update([
            'site_name' => 'Global Site',
            'default_title' => 'Global Title',
            'default_description' => 'Global Description',
            'default_robots' => 'index,follow',
        ]);
    });

    it('resolves global defaults when all other sources are empty', function (): void {
        $context = new SeoContext;

        $result = $this->seoResolver->resolve($context);

        expect($result->title)->toBe('Global Title')
            ->and($result->description)->toBe('Global Description')
            ->and($result->robots)->toBe('index,follow')
            ->and($result->ogSiteName)->toBe('Global Site');
    });

    it('priority: route SEO title overrides global default title', function (): void {
        SeoRoute::create([
            'route_name' => 'about',
            'title' => 'Route Title',
            'description' => 'Route Description',
        ]);

        $context = SeoContext::forRoute('about');

        $result = $this->seoResolver->resolve($context);

        expect($result->title)->toBe('Route Title')
            ->and($result->description)->toBe('Route Description')
            ->and($result->robots)->toBe('index,follow'); // fallback from global
    });

    it('priority: model SEO title overrides route SEO and global default titles', function (): void {
        SeoRoute::create([
            'route_name' => 'posts.show',
            'title' => 'Route Title',
        ]);

        $post = TestPost::create(['title' => 'Post']);

        SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id' => $post->id,
            'title' => 'Model SEO Title',
        ]);

        $context = new SeoContext(
            model: $post,
            routeName: 'posts.show',
        );

        $result = $this->seoResolver->resolve($context);

        expect($result->title)->toBe('Model SEO Title')
            ->and($result->description)->toBe('Global Description'); // fallback from global
    });

    it('priority: explicit runtime override title overrides model, route, and global titles', function (): void {
        SeoRoute::create([
            'route_name' => 'posts.show',
            'title' => 'Route Title',
        ]);

        $post = TestPost::create(['title' => 'Post']);

        SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id' => $post->id,
            'title' => 'Model SEO Title',
        ]);

        $context = new SeoContext(
            model: $post,
            routeName: 'posts.show',
            runtimeOverrides: [
                'title' => 'Runtime Override Title',
            ],
        );

        $result = $this->seoResolver->resolve($context);

        expect($result->title)->toBe('Runtime Override Title')
            ->and($result->description)->toBe('Global Description');
    });

    it('never allows a lower-priority resolver to overwrite a field set by a higher-priority resolver', function (): void {
        $post = TestPost::create(['title' => 'Post Title']);

        SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id' => $post->id,
            'title' => 'Model Title',
            'description' => 'Model Description',
        ]);

        SeoRoute::create([
            'route_name' => 'posts.show',
            'title' => 'Route Title',
            'description' => 'Route Description',
            'keywords' => 'route, keywords',
        ]);

        $context = new SeoContext(
            model: $post,
            routeName: 'posts.show',
            runtimeOverrides: [
                'title' => 'Runtime Title',
            ],
        );

        $result = $this->seoResolver->resolve($context);

        // Runtime title wins over model, route, global
        expect($result->title)->toBe('Runtime Title')
            // Model description wins over route, global
            ->and($result->description)->toBe('Model Description')
            // Route keywords wins over global
            ->and($result->keywords)->toBe('route, keywords')
            // Global robots wins (none specified higher)
            ->and($result->robots)->toBe('index,follow');
    });
});
