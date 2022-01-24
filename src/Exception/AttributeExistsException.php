<?php

namespace Pars\Pattern\Exception;

use Throwable;

class AttributeExistsException extends ExistsException
{
    public function __construct($message = "", $code = self::ATTRIBUTE_EXISTS_EXCEPTION_CODE, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
