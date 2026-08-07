<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Exceptions;

use RuntimeException;

/**
 * SeoException
 *
 * Base exception class for all exceptions thrown by the SEO Management package.
 *
 * Catching this class will catch all package-specific exceptions, making it easy
 * for host applications to handle SEO-related errors with a single catch block.
 */
class SeoException extends RuntimeException
{
    //
}
