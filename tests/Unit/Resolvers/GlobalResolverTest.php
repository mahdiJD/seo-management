<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mahdijd\SeoManagement\Contracts\SeoSettingsRepositoryInterface;
use Mahdijd\SeoManagement\DTOs\SeoContext;
use Mahdijd\SeoManagement\Services\Resolvers\GlobalResolver;

uses(RefreshDatabase::class);

describe('GlobalResolver', function (): void {
    beforeEach(function (): void {
        $this->repository = app(SeoSettingsRepositoryInterface::class);
        $this->resolver   = new GlobalResolver($this->repository);
    });

    it('resolves default global settings from database', function (): void {
        $this->repository->update([
            'site_name'            => 'Global Site Name',
            'default_title'        => 'Global Title Fallback',
            'default_description'  => 'Global Description Fallback',
            'default_canonical'    => 'https://example.com',
            'default_robots'       => 'index,follow',
            'default_og_type'      => 'website',
            'default_twitter_card' => 'summary_large_image',
        ]);

        $context = new SeoContext();

        $result = $this->resolver->resolve($context);

        expect($result->title)->toBe('Global Title Fallback')
            ->and($result->description)->toBe('Global Description Fallback')
            ->and($result->canonical)->toBe('https://example.com')
            ->and($result->robots)->toBe('index,follow')
            ->and($result->ogType)->toBe('website')
            ->and($result->ogSiteName)->toBe('Global Site Name')
            ->and($result->twitterCard)->toBe('summary_large_image');
    });
});
