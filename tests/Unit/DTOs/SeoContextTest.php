<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mahdijd\SeoManagement\DTOs\SeoContext;
use Mahdijd\SeoManagement\Tests\Fixtures\TestPost;

uses(RefreshDatabase::class);

describe('SeoContext DTO', function (): void {
    it('initialises with default null/empty properties', function (): void {
        $context = new SeoContext;

        expect($context->model)->toBeNull()
            ->and($context->routeName)->toBeNull()
            ->and($context->runtimeOverrides)->toBeArray()->toBeEmpty()
            ->and($context->requestUrl)->toBeNull()
            ->and($context->cacheKey)->toBeNull();
    });

    it('creates context via forModel static constructor', function (): void {
        $post = TestPost::create(['title' => 'Sample Post']);
        $context = SeoContext::forModel($post, ['title' => 'Override'], 'https://example.com/post');

        expect($context->model)->toBe($post)
            ->and($context->routeName)->toBeNull()
            ->and($context->runtimeOverrides)->toBe(['title' => 'Override'])
            ->and($context->requestUrl)->toBe('https://example.com/post');
    });

    it('creates context via forRoute static constructor', function (): void {
        $context = SeoContext::forRoute('home', ['title' => 'Home Override']);

        expect($context->model)->toBeNull()
            ->and($context->routeName)->toBe('home')
            ->and($context->runtimeOverrides)->toBe(['title' => 'Home Override']);
    });

    it('creates context via forRuntime static constructor', function (): void {
        $context = SeoContext::forRuntime(['title' => 'Search Results'], 'search-page-hash');

        expect($context->model)->toBeNull()
            ->and($context->routeName)->toBeNull()
            ->and($context->runtimeOverrides)->toBe(['title' => 'Search Results'])
            ->and($context->cacheKey)->toBe('search-page-hash');
    });

    it('is immutable readonly class', function (): void {
        $reflection = new ReflectionClass(SeoContext::class);
        expect($reflection->isReadOnly())->toBeTrue();
    });
});
