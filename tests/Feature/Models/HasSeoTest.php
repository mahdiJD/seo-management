<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mahdijd\SeoManagement\Models\SeoMetadata;
use Mahdijd\SeoManagement\Tests\Fixtures\TestPost;

uses(RefreshDatabase::class);

describe('HasSeo Trait', function (): void {
    it('provides polymorphic morphOne relationship to SeoMetadata', function (): void {
        $post = TestPost::create(['title' => 'Post with HasSeo', 'excerpt' => 'Excerpt']);

        $seo = $post->seo()->create([
            'title'       => 'SEO Title',
            'description' => 'SEO Description',
        ]);

        expect($seo)->toBeInstanceOf(SeoMetadata::class)
            ->and($post->seo)->not->toBeNull()
            ->and($post->seo->title)->toBe('SEO Title');
    });

    it('returns default fallback array from getSeoFallback()', function (): void {
        $post = TestPost::create(['title' => 'Post Title', 'excerpt' => 'Post Excerpt']);

        $fallback = $post->getSeoFallback();

        expect($fallback)->toBeArray()
            ->and($fallback['title'])->toBe('Post Title')
            ->and($fallback['description'])->toBe('Post Excerpt');
    });
});
