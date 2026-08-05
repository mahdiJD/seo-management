<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Mahdijd\SeoManagement\Events\SeoRendered;
use Mahdijd\SeoManagement\Events\SeoResolved;
use Mahdijd\SeoManagement\Models\SeoMetadata;
use Mahdijd\SeoManagement\Models\SeoRoute;
use Mahdijd\SeoManagement\Services\SeoManager;
use Mahdijd\SeoManagement\Tests\Fixtures\TestPost;

uses(RefreshDatabase::class);

describe('SeoManager Cache Hit & Miss', function (): void {
    beforeEach(function (): void {
        $this->seoManager = app(SeoManager::class);
    });

    it('renders model SEO on first miss and returns cached HTML string on subsequent calls', function (): void {
        $post = TestPost::create(['title' => 'Post']);

        SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id'   => $post->id,
            'title'        => 'Cached Model Title',
        ]);

        $html1 = $this->seoManager->renderForModel($post);
        $html2 = $this->seoManager->renderForModel($post);

        expect($html1)->toContain('<title>Cached Model Title</title>')
            ->and($html2)->toBe($html1);
    });

    it('dispatches SeoResolved and SeoRendered events during rendering', function (): void {
        Event::fake([SeoResolved::class, SeoRendered::class]);

        $post = TestPost::create(['title' => 'Post']);

        $this->seoManager->renderForModel($post);

        Event::assertDispatched(SeoResolved::class);
        Event::assertDispatched(SeoRendered::class);
    });

    it('fails gracefully and returns empty string if rendering throws an exception', function (): void {
        $post = TestPost::create(['title' => 'Post']);

        // Mock resolver to throw exception
        $mockResolver = Mockery::mock(\Mahdijd\SeoManagement\Contracts\SeoResolverInterface::class);
        $mockResolver->shouldReceive('resolve')->andThrow(new RuntimeException('Resolution failure'));

        $manager = new SeoManager(
            $mockResolver,
            app(\Mahdijd\SeoManagement\Contracts\SeoRendererInterface::class),
            app(\Mahdijd\SeoManagement\Contracts\SeoCacheManagerInterface::class)
        );

        $html = $manager->renderForModel($post);

        expect($html)->toBe('');
    });

    it('always queries DB when config seo.cache is false (model)', function (): void {
        config(['seo.cache' => false]);

        $this->seoManager = app(SeoManager::class);

        $post = TestPost::create(['title' => 'Post']);

        SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id'   => $post->id,
            'title'        => 'Uncached Model Title',
        ]);

        $html1 = $this->seoManager->renderForModel($post);

        // Update record in DB
        SeoMetadata::where('seoable_id', $post->id)->update(['title' => 'Updated Title']);

        // Refresh model to ensure new timestamp for cache key
        $post->refresh();

        $html2 = $this->seoManager->renderForModel($post);

        expect($html1)->toContain('<title>Uncached Model Title</title>')
            ->and($html2)->toContain('<title>Updated Title</title>');
    });

    it('renders route SEO on first miss and returns cached HTML string on subsequent calls', function (): void {
        SeoRoute::create([
            'route_name' => 'test.route',
            'title'      => 'Route Cached Title',
        ]);

        $html1 = $this->seoManager->renderForRoute('test.route');
        $html2 = $this->seoManager->renderForRoute('test.route');

        expect($html1)->toContain('<title>Route Cached Title</title>')
            ->and($html2)->toBe($html1);
    });

    it('always queries DB when config seo.cache is false (route)', function (): void {
        config(['seo.cache' => false]);

        $this->seoManager = app(SeoManager::class);

        SeoRoute::create([
            'route_name' => 'uncached.route',
            'title'      => 'Uncached Route Title',
        ]);

        $html1 = $this->seoManager->renderForRoute('uncached.route');

        SeoRoute::where('route_name', 'uncached.route')->update(['title' => 'Route Updated']);

        $html2 = $this->seoManager->renderForRoute('uncached.route');

        expect($html1)->toContain('<title>Uncached Route Title</title>')
            ->and($html2)->toContain('<title>Route Updated</title>');
    });
});
