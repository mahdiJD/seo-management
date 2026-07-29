<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mahdijd\SeoManagement\Contracts\SeoMetadataRepositoryInterface;
use Mahdijd\SeoManagement\Models\SeoMetadata;
use Mahdijd\SeoManagement\Repositories\SeoMetadataRepository;
use Mahdijd\SeoManagement\Tests\Fixtures\TestPost;

uses(RefreshDatabase::class);

describe('SeoMetadataRepository', function (): void {
    beforeEach(function (): void {
        $this->repository = new SeoMetadataRepository();
    });

    it('implements SeoMetadataRepositoryInterface', function (): void {
        expect($this->repository)->toBeInstanceOf(SeoMetadataRepositoryInterface::class);
    });

    it('returns null when no metadata record exists for a model', function (): void {
        $post = TestPost::create(['title' => 'Post Without SEO']);

        $result = $this->repository->findByModel($post);

        expect($result)->toBeNull();
    });

    it('finds existing metadata for a model by morph keys', function (): void {
        $post = TestPost::create(['title' => 'Post With SEO']);

        SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id'   => $post->id,
            'title'        => 'Custom Title',
        ]);

        $result = $this->repository->findByModel($post);

        expect($result)->not->toBeNull()
            ->and($result)->toBeInstanceOf(SeoMetadata::class)
            ->and($result->title)->toBe('Custom Title');
    });

    it('creates a new metadata record when saving for a model without existing metadata', function (): void {
        $post = TestPost::create(['title' => 'New Post']);

        $saved = $this->repository->save($post, [
            'title'       => 'Saved Title',
            'description' => 'Saved Description',
        ]);

        expect($saved)->toBeInstanceOf(SeoMetadata::class)
            ->and($saved->seoable_type)->toBe(TestPost::class)
            ->and($saved->seoable_id)->toBe($post->id)
            ->and($saved->title)->toBe('Saved Title')
            ->and($saved->description)->toBe('Saved Description');

        expect(SeoMetadata::count())->toBe(1);
    });

    it('updates existing metadata record when saving for a model with metadata', function (): void {
        $post = TestPost::create(['title' => 'Existing Post']);

        $this->repository->save($post, ['title' => 'Original Title']);

        $updated = $this->repository->save($post, ['title' => 'Updated Title']);

        expect($updated->title)->toBe('Updated Title')
            ->and(SeoMetadata::count())->toBe(1);
    });

    it('deletes an existing metadata record for a model', function (): void {
        $post = TestPost::create(['title' => 'Post to Delete']);
        $this->repository->save($post, ['title' => 'Title']);

        $deleted = $this->repository->delete($post);

        expect($deleted)->toBeTrue()
            ->and(SeoMetadata::count())->toBe(0);
    });

    it('returns false when deleting metadata for a model that has no record', function (): void {
        $post = TestPost::create(['title' => 'Post Without Record']);

        $deleted = $this->repository->delete($post);

        expect($deleted)->toBeFalse();
    });
});
