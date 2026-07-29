<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mahdijd\SeoManagement\Models\SeoSettings;

uses(RefreshDatabase::class);

describe('SeoSettings Model', function (): void {
    it('uses the correct table name', function (): void {
        $model = new SeoSettings();

        expect($model->getTable())->toBe('seo_settings');
    });

    it('casts default_json_ld to an array', function (): void {
        $jsonLdData = [
            '@context' => 'https://schema.org',
            '@type'    => 'Organization',
            'name'     => 'My Organization',
        ];

        $settings = SeoSettings::create([
            'site_name'       => 'My Site',
            'default_title'   => 'Default Title',
            'default_json_ld' => $jsonLdData,
        ]);

        $fetched = SeoSettings::find($settings->id);

        expect($fetched->default_json_ld)->toBeArray()
            ->and($fetched->default_json_ld)->toBe($jsonLdData);
    });

    it('allows mass assignment of all defined fillable attributes', function (): void {
        $data = [
            'site_name'             => 'Global Site Name',
            'default_title'         => 'Global Default Title',
            'default_description'   => 'Global Default Description',
            'default_canonical'     => 'https://example.com',
            'default_robots'        => 'index,follow',
            'default_og_image'      => 'https://example.com/default-og.jpg',
            'default_og_type'       => 'website',
            'default_og_site_name'  => 'Global Site Name',
            'default_twitter_card'  => 'summary_large_image',
            'default_twitter_image' => 'https://example.com/default-tw.jpg',
            'default_json_ld'       => ['@type' => 'Organization'],
        ];

        $settings = SeoSettings::create($data);

        expect($settings->site_name)->toBe('Global Site Name')
            ->and($settings->default_title)->toBe('Global Default Title')
            ->and($settings->default_description)->toBe('Global Default Description')
            ->and($settings->default_canonical)->toBe('https://example.com')
            ->and($settings->default_robots)->toBe('index,follow')
            ->and($settings->default_og_image)->toBe('https://example.com/default-og.jpg')
            ->and($settings->default_og_type)->toBe('website')
            ->and($settings->default_og_site_name)->toBe('Global Site Name')
            ->and($settings->default_twitter_card)->toBe('summary_large_image')
            ->and($settings->default_twitter_image)->toBe('https://example.com/default-tw.jpg')
            ->and($settings->default_json_ld)->toBe(['@type' => 'Organization']);
    });
});
