<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mahdijd\SeoManagement\Models\SeoRoute;

uses(RefreshDatabase::class);

describe('SeoRoute Model', function (): void {
    it('uses the correct table name', function (): void {
        $model = new SeoRoute();

        expect($model->getTable())->toBe('seo_routes');
    });

    it('casts json_ld to an array', function (): void {
        $jsonLdData = [
            '@context' => 'https://schema.org',
            '@type'    => 'WebPage',
            'name'     => 'About Us',
        ];

        $route = SeoRoute::create([
            'route_name' => 'about',
            'title'      => 'About Us',
            'json_ld'    => $jsonLdData,
        ]);

        $fetched = SeoRoute::find($route->id);

        expect($fetched->json_ld)->toBeArray()
            ->and($fetched->json_ld)->toBe($jsonLdData);
    });

    it('allows mass assignment of all defined fillable attributes', function (): void {
        $data = [
            'route_name'          => 'home',
            'title'               => 'Home Title',
            'description'         => 'Home Description',
            'keywords'            => 'home, welcome',
            'canonical'           => 'https://example.com/',
            'robots'              => 'index,follow',
            'og_title'            => 'Home OG Title',
            'og_description'      => 'Home OG Description',
            'og_image'            => 'https://example.com/home-og.jpg',
            'og_type'             => 'website',
            'og_url'              => 'https://example.com/',
            'og_site_name'        => 'Site Name',
            'twitter_card'        => 'summary_large_image',
            'twitter_title'       => 'Home Twitter Title',
            'twitter_description' => 'Home Twitter Description',
            'twitter_image'       => 'https://example.com/home-tw.jpg',
            'json_ld'             => ['@type' => 'WebSite'],
        ];

        $route = SeoRoute::create($data);

        expect($route->route_name)->toBe('home')
            ->and($route->title)->toBe('Home Title')
            ->and($route->description)->toBe('Home Description')
            ->and($route->keywords)->toBe('home, welcome')
            ->and($route->canonical)->toBe('https://example.com/')
            ->and($route->robots)->toBe('index,follow')
            ->and($route->og_title)->toBe('Home OG Title')
            ->and($route->og_description)->toBe('Home OG Description')
            ->and($route->og_image)->toBe('https://example.com/home-og.jpg')
            ->and($route->og_type)->toBe('website')
            ->and($route->og_url)->toBe('https://example.com/')
            ->and($route->og_site_name)->toBe('Site Name')
            ->and($route->twitter_card)->toBe('summary_large_image')
            ->and($route->twitter_title)->toBe('Home Twitter Title')
            ->and($route->twitter_description)->toBe('Home Twitter Description')
            ->and($route->twitter_image)->toBe('https://example.com/home-tw.jpg')
            ->and($route->json_ld)->toBe(['@type' => 'WebSite']);
    });
});
