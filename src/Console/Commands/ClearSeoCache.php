<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Console\Commands;

use Illuminate\Console\Command;
use Mahdijd\SeoManagement\Contracts\SeoCacheManagerInterface;

/**
 * ClearSeoCache
 *
 * Artisan command to flush all cached SEO HTML entries.
 */
class ClearSeoCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:cache:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all SEO cache entries';

    /**
     * Execute the console command.
     */
    public function handle(SeoCacheManagerInterface $cacheManager): int
    {
        $cacheManager->flush();

        $this->info('SEO cache cleared successfully.');

        return self::SUCCESS;
    }
}
