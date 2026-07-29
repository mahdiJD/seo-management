<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mahdijd\SeoManagement\Models\SeoMetadata;
use Mahdijd\SeoManagement\Tests\Fixtures\TestPost;

uses(RefreshDatabase::class);

describe('SeoMetadata Model', function (): void {
    it('uses the correct table name', function (): void {
        $model = new SeoMetadata();

        expect($model->getTable())->toBe('seo_metadata');
    });

    it('casts json_ld to an array', function (): void {
        $post = TestPost::create(['title' => 'Sample Post', 'excerpt' => 'Sample Excerpt']);

        $jsonLdData = [
            '@context' => 'https://schema.org',
            '@type'    => 'Article',
            'headline' => 'Sample Headline',
        ];

        $seo = SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id'   => $post->id,
            'title'        => 'SEO Title',
            'json_ld'      => $jsonLdData,
        ]);

        $fetched = SeoMetadata::find($seo->id);

        expect($fetched->json_ld)->toBeArray()
            ->and($fetched->json_ld)->toBe($jsonLdData);
    });

    it('belongs to a morphTo parent model', function (): void {
        $post = TestPost::create(['title' => 'Sample Post', 'excerpt' => 'Sample Excerpt']);

        $seo = SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id'   => $post->id,
            'title'        => 'Morph Title',
        ]);

        expect($seo->seoable)->toBeInstanceOf(TestPost::class)
            ->and($seo->seoable->id)->toBe($post->id)
            ->and($seo->seoable->title)->toBe('Sample Post');
    });

    it('allows mass assignment of all defined fillable attributes', function (): void {
        $data = [
            'seoable_type'        => TestPost::class,
            'seoable_id'          => 1,
            'title'               => 'Title',
            'description'         => 'Description',
            'keywords'            => 'kw1, kw2',
            'canonical'           => 'https://example.com/page',
            'robots'              => 'index,follow',
            'og_title'            => 'OG Title',
            'og_description'      => 'OG Description',
            'og_image'            => 'https://example.com/og.jpg',
            'og_type'             => 'article',
            'og_url'              => 'https://example.com/page',
            'og_site_name'        => 'Site Name',
            'twitter_card'        => 'summary_large_image',
            'twitter_title'       => 'Twitter Title',
            'twitter_description' => 'Twitter Description',
            'twitter_image'       => 'https://example.com/tw.jpg',
            'json_ld'             => ['@type' => 'WebPage'],
        ];

        $post = TestPost::create(['title' => 'Post']);
        $data['seoable_id'] = $post->id;

        $seo = SeoMetadata::create($data);

        expect($seo->title)->toBe('Title')
            ->and($seo->description)->toBe('Description')
            ->and($seo->keywords)->toBe('kw1, kw2')
            ->and($seo->canonical)->toBe('https://example.com/page')
            ->and($seo->robots)->toBe('index,follow')
            ->and($seo->og_title)->toBe('OG Title')
            ->and($seo->og_description)->toBe('OG Description')
            ->and($seo->og_image)->toBe('https://example.com/og.jpg')
            ->and($seo->og_type)->toBe('article')
            ->and($seo->og_url)->toBe('https://example.com/page')
            ->and($seo->og_site_name)->toBe('Site Name')
            ->and($seo->twitter_card)->toBe('summary_large_image')
            ->and($seo->twitter_title)->toBe('Twitter Title')
            ->and($seo->twitter_description)->toBe('Twitter Description')
            ->and($seo->twitter_image)->toBe('https://example.com/tw.jpg')
            ->and($seo->json_ld)->toBe(['@type' => 'WebPage']);
    });
});
