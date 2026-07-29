<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Mahdijd\SeoManagement\Contracts\SeoCacheManagerInterface;
use Mahdijd\SeoManagement\Models\SeoRoute;
use Mahdijd\SeoManagement\Services\SeoCacheManager;
use Mahdijd\SeoManagement\Tests\Fixtures\TestPost;

describe('SeoCacheManager', function (): void {
    beforeEach(function (): void {
        $this->cacheManager = app(SeoCacheManagerInterface::class);
        Cache::flush();
    });

    it('generates correct key formats for models, routes, settings, and runtime', function (): void {
        $post = new TestPost(['id' => 42]);
        $post->id = 42;

        $modelKey    = $this->cacheManager->modelKey($post);
        $routeKey    = $this->cacheManager->routeKey('about');
        $settingsKey = $this->cacheManager->settingsKey();
        $runtimeKey  = $this->cacheManager->runtimeKey('custom-id');

        expect($modelKey)->toContain('seo:model:')
            ->and($routeKey)->toContain('seo:route:about:')
            ->and($settingsKey)->toContain('seo:settings:')
            ->and($runtimeKey)->toBe('seo:runtime:custom-id');
    });

    it('stores callback result in cache on cache miss and returns cached value on hit', function (): void {
        $calls = 0;
        $callback = function () use (&$calls): string {
            $calls++;
            return '<title>Cached Title</title>';
        };

        $result1 = $this->cacheManager->remember('test-key', $callback);
        $result2 = $this->cacheManager->remember('test-key', $callback);

        expect($result1)->toBe('<title>Cached Title</title>')
            ->and($result2)->toBe('<title>Cached Title</title>')
            ->and($calls)->toBe(1);
    });

    it('forgets specified cache key', function (): void {
        $calls = 0;
        $callback = function () use (&$calls): string {
            $calls++;
            return '<title>Title</title>';
        };

        $this->cacheManager->remember('forget-key', $callback);
        $this->cacheManager->forget('forget-key');
        $this->cacheManager->remember('forget-key', $callback);

        expect($calls)->toBe(2);
    });

    it('bypasses cache when config seo.cache is false', function (): void {
        config(['seo.cache' => false]);

        $calls = 0;
        $callback = function () use (&$calls): string {
            $calls++;
            return '<title>Bypassed</title>';
        };

        $this->cacheManager->remember('bypass-key', $callback);
        $this->cacheManager->remember('bypass-key', $callback);

        expect($calls)->toBe(2);
    });
});
