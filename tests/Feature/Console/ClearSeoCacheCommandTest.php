<?php

declare(strict_types=1);

describe('seo:cache:clear Artisan Command', function (): void {
    it('flushes SEO cache and outputs success message', function (): void {
        $this->artisan('seo:cache:clear')
            ->expectsOutput('SEO cache cleared successfully.')
            ->assertExitCode(0);
    });
});
