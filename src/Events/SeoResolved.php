<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Events;

use Mahdijd\SeoManagement\DTOs\SeoContext;
use Mahdijd\SeoManagement\DTOs\SeoData;

/**
 * SeoResolved
 *
 * Event dispatched after SEO metadata has been resolved by the resolver chain.
 */
class SeoResolved
{
    /**
     * Create a new SeoResolved event instance.
     */
    public function __construct(
        public readonly SeoData $data,
        public readonly SeoContext $context,
    ) {}
}
