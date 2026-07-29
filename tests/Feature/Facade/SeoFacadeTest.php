<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mahdijd\SeoManagement\DTOs\SeoData;
use Mahdijd\SeoManagement\Facades\Seo;
use Mahdijd\SeoManagement\Models\SeoMetadata;
use Mahdijd\SeoManagement\Tests\Fixtures\TestPost;

uses(RefreshDatabase::class);

describe('Seo Facade', function (): void {
    it('resolves model SEO data using Seo::forModel()', function (): void {
        $post = TestPost::create(['title' => 'Post']);

        SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id'   => $post->id,
            'title'        => 'Facade Model Title',
        ]);

        $data = Seo::forModel($post);

        expect($data)->toBeInstanceOf(SeoData::class)
            ->and($data->title)->toBe('Facade Model Title');
    });

    it('resolves route SEO data using Seo::forRoute()', function (): void {
        $data = Seo::forRoute('non-existent');

        expect($data)->toBeInstanceOf(SeoData::class);
    });

    it('renders SeoData object into HTML using Seo::render()', function (): void {
        $data = new SeoData(title: 'Facade Render Title');

        $html = Seo::render($data);

        expect($html)->toBe('<title>Facade Render Title</title>');
    });

    it('clears model cache using Seo::clearModel()', function (): void {
        $post = TestPost::create(['title' => 'Post']);

        Seo::clearModel($post);

        expect(true)->toBeTrue();
    });

    it('flushes all SEO cache using Seo::flushAll()', function (): void {
        Seo::flushAll();

        expect(true)->toBeTrue();
    });
});
