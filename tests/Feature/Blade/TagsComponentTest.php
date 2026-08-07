<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Mahdijd\SeoManagement\Contracts\SeoSettingsRepositoryInterface;
use Mahdijd\SeoManagement\Models\SeoMetadata;
use Mahdijd\SeoManagement\Tests\Fixtures\TestPost;

uses(RefreshDatabase::class);

describe('<x-seo::tags /> Blade Component', function (): void {
    it('renders model SEO when passed a model attribute', function (): void {
        $post = TestPost::create(['title' => 'Post']);

        SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id' => $post->id,
            'title' => 'Blade Model SEO Title',
        ]);

        $rendered = Blade::render('<x-seo::tags :model="$post" />', ['post' => $post]);

        expect($rendered)->toContain('<title>Blade Model SEO Title</title>');
    });

    it('renders custom runtime title override attribute', function (): void {
        $rendered = Blade::render('<x-seo::tags title="Custom Blade Title" />');

        expect($rendered)->toContain('<title>Custom Blade Title</title>');
    });

    it('prioritises runtime attribute override over model SEO data', function (): void {
        $post = TestPost::create(['title' => 'Post']);

        SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id' => $post->id,
            'title' => 'DB Model Title',
        ]);

        $rendered = Blade::render('<x-seo::tags :model="$post" title="Runtime Title Wins" />', ['post' => $post]);

        expect($rendered)->toContain('<title>Runtime Title Wins</title>');
    });

    it('caches output when cacheKey attribute is specified', function (): void {
        $rendered1 = Blade::render('<x-seo::tags title="Cached Title" cacheKey="unique-key-1" />');
        $rendered2 = Blade::render('<x-seo::tags title="Different Title" cacheKey="unique-key-1" />');

        expect($rendered1)->toContain('<title>Cached Title</title>')
            ->and($rendered2)->toContain('<title>Cached Title</title>');
    });

    it('uses global defaults when no attributes are provided', function (): void {
        app(SeoSettingsRepositoryInterface::class)->update([
            'default_title' => 'Global Default Title',
            'default_description' => 'Global Default Description',
        ]);

        $rendered = Blade::render('<x-seo::tags />');

        expect($rendered)->toContain('<title>Global Default Title</title>')
            ->and($rendered)->toContain('content="Global Default Description"');
    });

    it('does not render empty meta tags in output', function (): void {
        $rendered = Blade::render('<x-seo::tags title="Only Title" />');

        expect($rendered)->toContain('<title>Only Title</title>')
            ->and($rendered)->not->toContain('name="description"')
            ->and($rendered)->not->toContain('name="keywords"')
            ->and($rendered)->not->toContain('rel="canonical"');
    });
});
