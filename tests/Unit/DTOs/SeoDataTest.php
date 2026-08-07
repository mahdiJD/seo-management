<?php

declare(strict_types=1);

use Mahdijd\SeoManagement\DTOs\SeoData;

describe('SeoData DTO', function (): void {
    it('initialises with all properties set to null by default', function (): void {
        $seoData = new SeoData;

        expect($seoData->title)->toBeNull()
            ->and($seoData->description)->toBeNull()
            ->and($seoData->keywords)->toBeNull()
            ->and($seoData->canonical)->toBeNull()
            ->and($seoData->robots)->toBeNull()
            ->and($seoData->ogTitle)->toBeNull()
            ->and($seoData->ogDescription)->toBeNull()
            ->and($seoData->ogImage)->toBeNull()
            ->and($seoData->ogType)->toBeNull()
            ->and($seoData->ogUrl)->toBeNull()
            ->and($seoData->ogSiteName)->toBeNull()
            ->and($seoData->twitterCard)->toBeNull()
            ->and($seoData->twitterTitle)->toBeNull()
            ->and($seoData->twitterDescription)->toBeNull()
            ->and($seoData->twitterImage)->toBeNull()
            ->and($seoData->jsonLd)->toBeNull();
    });

    it('provides working getter methods matching property names', function (): void {
        $jsonLd = ['@type' => 'Organization', 'name' => 'Acme Corp'];

        $seoData = new SeoData(
            title: 'Sample Title',
            description: 'Sample Description',
            keywords: 'seo, laravel',
            canonical: 'https://example.com/canonical',
            robots: 'index,follow',
            ogTitle: 'OG Title',
            ogDescription: 'OG Description',
            ogImage: 'https://example.com/og.png',
            ogType: 'website',
            ogUrl: 'https://example.com/og',
            ogSiteName: 'Acme',
            twitterCard: 'summary_large_image',
            twitterTitle: 'Twitter Title',
            twitterDescription: 'Twitter Description',
            twitterImage: 'https://example.com/tw.png',
            jsonLd: $jsonLd,
        );

        expect($seoData->title())->toBe('Sample Title')
            ->and($seoData->description())->toBe('Sample Description')
            ->and($seoData->keywords())->toBe('seo, laravel')
            ->and($seoData->canonical())->toBe('https://example.com/canonical')
            ->and($seoData->robots())->toBe('index,follow')
            ->and($seoData->ogTitle())->toBe('OG Title')
            ->and($seoData->ogDescription())->toBe('OG Description')
            ->and($seoData->ogImage())->toBe('https://example.com/og.png')
            ->and($seoData->ogType())->toBe('website')
            ->and($seoData->ogUrl())->toBe('https://example.com/og')
            ->and($seoData->ogSiteName())->toBe('Acme')
            ->and($seoData->twitterCard())->toBe('summary_large_image')
            ->and($seoData->twitterTitle())->toBe('Twitter Title')
            ->and($seoData->twitterDescription())->toBe('Twitter Description')
            ->and($seoData->twitterImage())->toBe('https://example.com/tw.png')
            ->and($seoData->jsonLd())->toBe($jsonLd);
    });

    it('is immutable and cannot have properties modified', function (): void {
        $seoData = new SeoData(title: 'Initial Title');

        $reflection = new ReflectionClass(SeoData::class);
        expect($reflection->isReadOnly())->toBeTrue();
    });

    it('converts to an associative array using toArray()', function (): void {
        $seoData = new SeoData(
            title: 'Title',
            description: 'Description',
            jsonLd: ['@type' => 'WebPage']
        );

        $array = $seoData->toArray();

        expect($array)->toBeArray()
            ->and($array['title'])->toBe('Title')
            ->and($array['description'])->toBe('Description')
            ->and($array['jsonLd'])->toBe(['@type' => 'WebPage'])
            ->and($array['canonical'])->toBeNull();
    });
});
