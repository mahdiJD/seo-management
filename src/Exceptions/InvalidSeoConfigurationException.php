<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Exceptions;

/**
 * InvalidSeoConfigurationException
 *
 * Thrown when the SEO package configuration is invalid or contains
 * values that cannot be used to initialise the package correctly.
 *
 * Examples of conditions that trigger this exception:
 * - An unsupported cache store is specified in config/seo.php.
 * - A required configuration key is missing after publishing.
 * - A numeric configuration value is out of its valid range.
 */
class InvalidSeoConfigurationException extends SeoException
{
    //
}
