<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Mahdijd\SeoManagement\Contracts\SeoCacheManagerInterface;
use Mahdijd\SeoManagement\Events\SeoCacheCleared;
use Mahdijd\SeoManagement\Models\SeoMetadata;
use Mahdijd\SeoManagement\Models\SeoRoute;
use Mahdijd\SeoManagement\Models\SeoSettings;
use Mahdijd\SeoManagement\Services\SeoManager;
use Mahdijd\SeoManagement\Tests\Fixtures\TestPost;

uses(RefreshDatabase::class);

describe('Cache Invalidation Observers', function (): void {
    beforeEach(function (): void {
        $this->seoManager = app(SeoManager::class);
        $this->cacheManager = app(SeoCacheManagerInterface::class);
    });

    it('invalidates model SEO cache when model SeoMetadata record is saved or updated', function (): void {
        Event::fake([SeoCacheCleared::class]);

        $post = TestPost::create(['title' => 'Post']);

        $metadata = SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id' => $post->id,
            'title' => 'First Title',
        ]);

        Event::assertDispatched(SeoCacheCleared::class);

        $metadata->update(['title' => 'Updated Title']);

        Event::assertDispatched(SeoCacheCleared::class);
    });

    it('invalidates route SEO cache when SeoRoute record is saved or updated', function (): void {
        Event::fake([SeoCacheCleared::class]);

        $route = SeoRoute::create([
            'route_name' => 'contact',
            'title' => 'Contact Us',
        ]);

        Event::assertDispatched(SeoCacheCleared::class);

        $route->update(['title' => 'New Contact Title']);

        Event::assertDispatched(SeoCacheCleared::class);
    });

    it('flushes all SEO cache when SeoSettings is updated', function (): void {
        Event::fake([SeoCacheCleared::class]);

        $settings = SeoSettings::firstOrCreate([]);
        $settings->update(['site_name' => 'New Global Site Name']);

        Event::assertDispatched(SeoCacheCleared::class, function ($event) {
            return $event->cacheKey === 'all';
        });
    });

    it('invalidates model SEO cache when HasSeo model is saved or updated', function (): void {
        Event::fake([SeoCacheCleared::class]);

        $post = TestPost::create(['title' => 'Initial Title']);

        Event::assertDispatched(SeoCacheCleared::class);

        $post->update(['title' => 'Title Changed']);

        Event::assertDispatched(SeoCacheCleared::class);
    });

    it('invalidates model SEO cache when SeoMetadata record is deleted', function (): void {
        $post = TestPost::create(['title' => 'Post']);

        $metadata = SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id' => $post->id,
            'title' => 'To Be Deleted',
        ]);

        Event::fake([SeoCacheCleared::class]);

        $metadata->delete();

        Event::assertDispatched(SeoCacheCleared::class);
    });
});
