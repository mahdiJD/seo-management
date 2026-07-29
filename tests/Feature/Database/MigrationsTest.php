<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Mahdijd\SeoManagement\Models\SeoMetadata;
use Mahdijd\SeoManagement\Models\SeoRoute;
use Mahdijd\SeoManagement\Tests\Fixtures\TestPost;

uses(RefreshDatabase::class);

describe('Migrations', function (): void {
    it('creates the seo_metadata table with correct columns and constraints', function (): void {
        expect(Schema::hasTable('seo_metadata'))->toBeTrue()
            ->and(Schema::hasColumns('seo_metadata', [
                'id',
                'seoable_type',
                'seoable_id',
                'title',
                'description',
                'keywords',
                'canonical',
                'robots',
                'og_title',
                'og_description',
                'og_image',
                'og_type',
                'og_url',
                'og_site_name',
                'twitter_card',
                'twitter_title',
                'twitter_description',
                'twitter_image',
                'json_ld',
                'created_at',
                'updated_at',
            ]))->toBeTrue();
    });

    it('enforces unique constraint on seoable_type and seoable_id in seo_metadata', function (): void {
        $post = TestPost::create(['title' => 'Test Post', 'excerpt' => 'Excerpt']);

        SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id'   => $post->id,
            'title'        => 'First Title',
        ]);

        expect(fn () => SeoMetadata::create([
            'seoable_type' => TestPost::class,
            'seoable_id'   => $post->id,
            'title'        => 'Second Title',
        ]))->toThrow(Illuminate\Database\QueryException::class);
    });

    it('creates the seo_routes table with correct columns and constraints', function (): void {
        expect(Schema::hasTable('seo_routes'))->toBeTrue()
            ->and(Schema::hasColumns('seo_routes', [
                'id',
                'route_name',
                'title',
                'description',
                'keywords',
                'canonical',
                'robots',
                'og_title',
                'og_description',
                'og_image',
                'og_type',
                'og_url',
                'og_site_name',
                'twitter_card',
                'twitter_title',
                'twitter_description',
                'twitter_image',
                'json_ld',
                'created_at',
                'updated_at',
            ]))->toBeTrue();
    });

    it('enforces unique constraint on route_name in seo_routes', function (): void {
        SeoRoute::create([
            'route_name' => 'home',
            'title'      => 'Home Title',
        ]);

        expect(fn () => SeoRoute::create([
            'route_name' => 'home',
            'title'      => 'Duplicate Home Title',
        ]))->toThrow(Illuminate\Database\QueryException::class);
    });

    it('creates the seo_settings table with correct columns', function (): void {
        expect(Schema::hasTable('seo_settings'))->toBeTrue()
            ->and(Schema::hasColumns('seo_settings', [
                'id',
                'site_name',
                'default_title',
                'default_description',
                'default_canonical',
                'default_robots',
                'default_og_image',
                'default_og_type',
                'default_og_site_name',
                'default_twitter_card',
                'default_twitter_image',
                'default_json_ld',
                'created_at',
                'updated_at',
            ]))->toBeTrue();
    });

    it('rolls back migrations cleanly', function (): void {
        $this->artisan('migrate:rollback')->assertExitCode(0);

        expect(Schema::hasTable('seo_metadata'))->toBeFalse()
            ->and(Schema::hasTable('seo_routes'))->toBeFalse()
            ->and(Schema::hasTable('seo_settings'))->toBeFalse();
    });
});
