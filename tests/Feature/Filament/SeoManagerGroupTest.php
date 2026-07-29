<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mahdijd\SeoManagement\Filament\Forms\SeoManagerGroup;
use Mahdijd\SeoManagement\Models\SeoMetadata;
use Mahdijd\SeoManagement\Tests\Fixtures\TestPost;

uses(RefreshDatabase::class);

describe('SeoManagerGroup', function (): void {
    it('creates group component with schema', function (): void {
        $group = SeoManagerGroup::make();

        expect($group)->not->toBeNull();
    });

    it('saves SEO metadata record via save() helper method', function (): void {
        $post = TestPost::create(['title' => 'Post']);

        SeoManagerGroup::save($post, [
            'title'       => 'Saved Form Title',
            'description' => 'Saved Form Description',
        ]);

        expect(SeoMetadata::count())->toBe(1);

        $saved = SeoMetadata::first();
        expect($saved->title)->toBe('Saved Form Title')
            ->and($saved->description)->toBe('Saved Form Description');
    });

    it('deletes empty SEO record when delete_empty_records is enabled in config', function (): void {
        config(['seo.filament.delete_empty_records' => true]);

        $post = TestPost::create(['title' => 'Post']);
        SeoManagerGroup::save($post, ['title' => 'Initial Title']);

        expect(SeoMetadata::count())->toBe(1);

        SeoManagerGroup::save($post, ['title' => '', 'description' => '']);

        expect(SeoMetadata::count())->toBe(0);
    });
});
