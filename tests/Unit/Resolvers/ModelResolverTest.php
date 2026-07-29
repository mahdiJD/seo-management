<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mahdijd\SeoManagement\Contracts\SeoMetadataRepositoryInterface;
use Mahdijd\SeoManagement\DTOs\SeoContext;
use Mahdijd\SeoManagement\Models\SeoMetadata;
use Mahdijd\SeoManagement\Services\Resolvers\ModelResolver;
use Mahdijd\SeoManagement\Tests\Fixtures\TestPost;

uses(RefreshDatabase::class);

describe('ModelResolver', function (): void {
    beforeEach(function (): void {
        $this->repository = app(SeoMetadataRepositoryInterface::class);
        $this->resolver   = new ModelResolver($this->repository);
    });

    it('returns empty SeoData when context model is null', function (): void {
        $context = new SeoContext();

        $result = $this->resolver->resolve($context);

        expect($result->title)->toBeNull()
            ->and($result->description)->toBeNull();
    });

    it('resolves model SEO metadata from database', function (): void {
        $post = TestPost::create(['title' => 'Post Title', 'excerpt' => 'Post Excerpt']);

        SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id'   => $post->id,
            'title'        => 'Custom DB SEO Title',
            'description'  => 'Custom DB Meta Description',
            'og_title'     => 'Custom OG Title',
        ]);

        $context = SeoContext::forModel($post);

        $result = $this->resolver->resolve($context);

        expect($result->title)->toBe('Custom DB SEO Title')
            ->and($result->description)->toBe('Custom DB Meta Description')
            ->and($result->ogTitle)->toBe('Custom OG Title');
    });

    it('applies getSeoFallback() values for missing fields when no DB record exists', function (): void {
        $modelWithFallback = new class () extends TestPost {
            public function getSeoFallback(): array
            {
                return [
                    'title'       => 'Fallback Title',
                    'description' => 'Fallback Description',
                ];
            }
        };
        $modelWithFallback->save();

        $context = SeoContext::forModel($modelWithFallback);

        $result = $this->resolver->resolve($context);

        expect($result->title)->toBe('Fallback Title')
            ->and($result->description)->toBe('Fallback Description');
    });

    it('prioritises database metadata record over getSeoFallback() values', function (): void {
        $modelWithFallback = new class () extends TestPost {
            public function getSeoFallback(): array
            {
                return [
                    'title'       => 'Fallback Title',
                    'description' => 'Fallback Description',
                ];
            }
        };
        $modelWithFallback->save();

        SeoMetadata::create([
            'seoable_type' => $modelWithFallback->getMorphClass(),
            'seoable_id'   => $modelWithFallback->id,
            'title'        => 'DB Title Wins',
        ]);

        $context = SeoContext::forModel($modelWithFallback);

        $result = $this->resolver->resolve($context);

        expect($result->title)->toBe('DB Title Wins')
            ->and($result->description)->toBe('Fallback Description');
    });
});
