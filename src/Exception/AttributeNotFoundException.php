<?php

declare(strict_types=1);

/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Core\Exception;

use Throwable;

class AttributeNotFoundException extends NotFoundException
{
    public function __construct($message = "", $code = self::ATTRIBUTE_NOT_FOUND_EXCEPTION_CODE, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
