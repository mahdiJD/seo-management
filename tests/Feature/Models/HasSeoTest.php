<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Mahdijd\SeoManagement\Events\SeoCacheCleared;
use Mahdijd\SeoManagement\Models\SeoMetadata;
use Mahdijd\SeoManagement\Tests\Fixtures\TestPost;

uses(RefreshDatabase::class);

describe('HasSeo Trait', function (): void {
    it('provides polymorphic morphOne relationship to SeoMetadata', function (): void {
        $post = TestPost::create(['title' => 'Post with HasSeo', 'excerpt' => 'Excerpt']);

        $seo = $post->seo()->create([
            'title' => 'SEO Title',
            'description' => 'SEO Description',
        ]);

        expect($seo)->toBeInstanceOf(SeoMetadata::class)
            ->and($post->seo)->not->toBeNull()
            ->and($post->seo->title)->toBe('SEO Title');
    });

    it('creates SEO record via relationship and persists to database', function (): void {
        $post = TestPost::create(['title' => 'New Post', 'excerpt' => 'Excerpt']);

        $post->seo()->create([
            'title' => 'Created Via Relationship',
            'description' => 'Relationship Description',
        ]);

        expect(SeoMetadata::count())->toBe(1);

        $metadata = SeoMetadata::first();
        expect($metadata->title)->toBe('Created Via Relationship')
            ->and($metadata->seoable_type)->toBe(TestPost::class)
            ->and($metadata->seoable_id)->toBe($post->id);
    });

    it('returns default fallback array from getSeoFallback()', function (): void {
        $post = TestPost::create(['title' => 'Post Title', 'excerpt' => 'Post Excerpt']);

        $fallback = $post->getSeoFallback();

        expect($fallback)->toBeArray()
            ->and($fallback['title'])->toBe('Post Title')
            ->and($fallback['description'])->toBe('Post Excerpt');
    });

    it('allows overriding getSeoFallback() on the model', function (): void {
        $modelWithOverride = new class extends TestPost
        {
            public function getSeoFallback(): array
            {
                return [
                    'title' => 'Custom Fallback Title',
                    'description' => 'Custom Fallback Description',
                    'ogTitle' => 'Custom OG Fallback',
                ];
            }
        };
        $modelWithOverride->title = 'Ignored';
        $modelWithOverride->save();

        $fallback = $modelWithOverride->getSeoFallback();

        expect($fallback['title'])->toBe('Custom Fallback Title')
            ->and($fallback['description'])->toBe('Custom Fallback Description')
            ->and($fallback['ogTitle'])->toBe('Custom OG Fallback');
    });

    it('fires SeoModelObserver cache clearing event when HasSeo model is saved', function (): void {
        Event::fake([SeoCacheCleared::class]);

        $post = TestPost::create(['title' => 'Observer Test']);

        Event::assertDispatched(SeoCacheCleared::class);
    });

    it('fires SeoModelObserver cache clearing event when HasSeo model is updated', function (): void {
        $post = TestPost::create(['title' => 'Before Update']);

        Event::fake([SeoCacheCleared::class]);

        $post->update(['title' => 'After Update']);

        Event::assertDispatched(SeoCacheCleared::class);
    });
});
