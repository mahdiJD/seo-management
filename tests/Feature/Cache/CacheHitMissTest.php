<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Mahdijd\SeoManagement\Events\SeoRendered;
use Mahdijd\SeoManagement\Events\SeoResolved;
use Mahdijd\SeoManagement\Models\SeoMetadata;
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
});
