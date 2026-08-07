<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Events;

use Mahdijd\SeoManagement\DTOs\SeoContext;

/**
 * SeoRendered
 *
 * Event dispatched after SEO HTML tags have been rendered.
 */
class SeoRendered
{
    /**
     * Create a new SeoRendered event instance.
     */
    public function __construct(
        public readonly string $html,
        public readonly SeoContext $context,
    ) {}
}
