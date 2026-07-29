<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Contracts;

use Mahdijd\SeoManagement\DTOs\SeoData;

/**
 * SeoRendererInterface
 *
 * Contract for services that render a SeoData DTO into HTML tags.
 */
interface SeoRendererInterface
{
    /**
     * Render the given SeoData object into HTML meta tags.
     *
     * @param  SeoData  $data
     * @return string
     */
    public function render(SeoData $data): string;
}
