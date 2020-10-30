<?php


namespace Niceshops\Core\Exception;


use Throwable;

class AttributeLockException extends LockException
{
    public function __construct($message = "", $code = self::ATTRIBUTE_LOCK_EXCEPTION_CODE, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
