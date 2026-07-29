<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Contracts;

use Mahdijd\SeoManagement\DTOs\SeoContext;
use Mahdijd\SeoManagement\DTOs\SeoData;

/**
 * SeoResolverInterface
 *
 * Contract for services that resolve metadata from context into a SeoData DTO.
 */
interface SeoResolverInterface
{
    /**
     * Resolve SEO data for the given context.
     *
     * @param  SeoContext  $context
     * @return SeoData
     */
    public function resolve(SeoContext $context): SeoData;
}
