<?php

declare(strict_types=1);

use Mahdijd\SeoManagement\DTOs\SeoContext;
use Mahdijd\SeoManagement\Services\Resolvers\RuntimeResolver;

describe('RuntimeResolver', function (): void {
    beforeEach(function (): void {
        $this->resolver = new RuntimeResolver();
    });

    it('returns empty SeoData when no runtime overrides exist', function (): void {
        $context = new SeoContext();

        $result = $this->resolver->resolve($context);

        expect($result->title)->toBeNull()
            ->and($result->description)->toBeNull()
            ->and($result->jsonLd)->toBeNull();
    });

    it('resolves explicit runtime overrides into SeoData properties', function (): void {
        $context = new SeoContext(
            runtimeOverrides: [
                'title'       => 'Runtime Title',
                'description' => 'Runtime Description',
                'keywords'    => 'key1, key2',
                'canonical'   => 'https://example.com/runtime',
                'robots'      => 'noindex,nofollow',
                'ogTitle'     => 'Runtime OG Title',
                'jsonLd'      => ['@type' => 'WebPage'],
            ]
        );

        $result = $this->resolver->resolve($context);

        expect($result->title)->toBe('Runtime Title')
            ->and($result->description)->toBe('Runtime Description')
            ->and($result->keywords)->toBe('key1, key2')
            ->and($result->canonical)->toBe('https://example.com/runtime')
            ->and($result->robots)->toBe('noindex,nofollow')
            ->and($result->ogTitle)->toBe('Runtime OG Title')
            ->and($result->jsonLd)->toBe(['@type' => 'WebPage'])
            ->and($result->ogDescription)->toBeNull();
    });

    it('resolves partial overrides leaving unspecified fields null', function (): void {
        $context = new SeoContext(
            runtimeOverrides: [
                'title' => 'Only Title',
            ]
        );

        $result = $this->resolver->resolve($context);

        expect($result->title)->toBe('Only Title')
            ->and($result->description)->toBeNull()
            ->and($result->keywords)->toBeNull()
            ->and($result->canonical)->toBeNull()
            ->and($result->robots)->toBeNull()
            ->and($result->ogTitle)->toBeNull()
            ->and($result->ogDescription)->toBeNull()
            ->and($result->ogImage)->toBeNull()
            ->and($result->twitterCard)->toBeNull()
            ->and($result->jsonLd)->toBeNull();
    });
});
