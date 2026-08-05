<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mahdijd\SeoManagement\Contracts\SeoSettingsRepositoryInterface;
use Mahdijd\SeoManagement\Models\SeoMetadata;
use Mahdijd\SeoManagement\Models\SeoRoute;
use Mahdijd\SeoManagement\Services\SeoManager;
use Mahdijd\SeoManagement\Tests\Fixtures\TestPost;

uses(RefreshDatabase::class);

describe('Complete Resolution Flow — End-to-End Integration', function (): void {
    beforeEach(function (): void {
        $this->seoManager = app(SeoManager::class);

        // Setup global defaults
        app(SeoSettingsRepositoryInterface::class)->update([
            'site_name'            => 'My Global Site',
            'default_title'        => 'Global Default Title',
            'default_description'  => 'Global Default Description',
            'default_robots'       => 'index,follow',
            'default_og_type'      => 'website',
            'default_twitter_card' => 'summary_large_image',
        ]);
    });

    it('Scenario A: model with SEO record + global defaults → model SEO wins for populated fields', function (): void {
        $post = TestPost::create(['title' => 'My Post', 'excerpt' => 'Excerpt']);

        SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id'   => $post->id,
            'title'        => 'Model SEO Title',
            'description'  => 'Model SEO Description',
        ]);

        $html = $this->seoManager->renderForModel($post);

        // Model SEO wins for title and description
        expect($html)->toContain('<title>Model SEO Title</title>')
            ->and($html)->toContain('content="Model SEO Description"')
            // Global defaults fill in missing fields
            ->and($html)->toContain('content="index,follow"')
            ->and($html)->toContain('og:type')
            ->and($html)->toContain('twitter:card');
    });

    it('Scenario B: model with no SEO record + fallback + global defaults → fallback wins for title/description', function (): void {
        $post = TestPost::create(['title' => 'Fallback Post Title', 'excerpt' => 'Fallback Excerpt']);

        // No SeoMetadata record; TestPost::getSeoFallback() returns title => $this->title, description => $this->excerpt

        $html = $this->seoManager->renderForModel($post);

        // Model fallback wins for title and description
        expect($html)->toContain('<title>Fallback Post Title</title>')
            ->and($html)->toContain('content="Fallback Excerpt"')
            // Global defaults fill remaining fields
            ->and($html)->toContain('content="index,follow"');
    });

    it('Scenario C: runtime override + model SEO → runtime wins for overridden fields', function (): void {
        $post = TestPost::create(['title' => 'Post', 'excerpt' => 'Excerpt']);

        SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id'   => $post->id,
            'title'        => 'Model Title',
            'description'  => 'Model Description',
            'keywords'     => 'model,keywords',
        ]);

        $data = $this->seoManager->resolveModel($post, [
            'title' => 'Runtime Override Title',
        ]);

        // Runtime wins for title
        expect($data->title)->toBe('Runtime Override Title')
            // Model wins for description (not overridden by runtime)
            ->and($data->description)->toBe('Model Description')
            // Model wins for keywords
            ->and($data->keywords)->toBe('model,keywords')
            // Global fills robots (not set by model or runtime)
            ->and($data->robots)->toBe('index,follow');
    });

    it('Scenario D: all four priority levels exercised end-to-end', function (): void {
        $post = TestPost::create(['title' => 'Post']);

        SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id'   => $post->id,
            'title'        => 'Model Title',
            'description'  => 'Model Description',
        ]);

        SeoRoute::create([
            'route_name' => 'posts.show',
            'title'      => 'Route Title',
            'keywords'   => 'route,seo,keywords',
        ]);

        $data = $this->seoManager->resolveModel($post, [
            'title' => 'Runtime Title Wins All',
        ]);

        // Runtime wins for title (highest priority)
        expect($data->title)->toBe('Runtime Title Wins All')
            // Model wins for description (higher than route and global)
            ->and($data->description)->toBe('Model Description')
            // Global fills robots (no higher source set it)
            ->and($data->robots)->toBe('index,follow')
            // Global fills og:type
            ->and($data->ogType)->toBe('website');
    });

    it('renders complete HTML with all tag types from multiple resolution sources', function (): void {
        $post = TestPost::create(['title' => 'Post', 'excerpt' => 'Excerpt']);

        SeoMetadata::create([
            'seoable_type'   => TestPost::class,
            'seoable_id'     => $post->id,
            'title'          => 'Full HTML Title',
            'description'    => 'Full HTML Description',
            'og_title'       => 'OG Title',
            'og_description' => 'OG Description',
            'twitter_title'  => 'Twitter Title',
            'json_ld'        => ['@type' => 'Article', 'headline' => 'Test'],
        ]);

        $html = $this->seoManager->renderForModel($post);

        expect($html)->toContain('<title>Full HTML Title</title>')
            ->and($html)->toContain('name="description"')
            ->and($html)->toContain('og:title')
            ->and($html)->toContain('og:description')
            ->and($html)->toContain('twitter:title')
            ->and($html)->toContain('application/ld+json')
            ->and($html)->toContain('"headline": "Test"');
    });
});
