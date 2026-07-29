<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Mahdijd\SeoManagement\Events\SeoCacheCleared;

describe('seo:cache:clear Artisan Command', function (): void {
    it('flushes SEO cache and outputs success message', function (): void {
        Event::fake([SeoCacheCleared::class]);

        $this->artisan('seo:cache:clear')
            ->expectsOutput('SEO cache cleared successfully.')
            ->assertExitCode(0);

        Event::assertDispatched(SeoCacheCleared::class, function ($event) {
            return $event->cacheKey === 'all';
        });
    });
});
