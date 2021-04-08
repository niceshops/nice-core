<?php

declare(strict_types=1);

/**
 * @see       https://github.com/Pars/pars-patterns for the canonical source repository
 * @license   https://github.com/Pars/pars-patterns/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Patterns\Exception;

use Throwable;

/**
 * Class Exception
 * @package Pars\Patterns
 */
class CoreException extends \Exception
{
    const BASIC_EXCEPTION_CODE = 1000;
    const API_EXCEPTION_CODE = 2000;
    const LOCK_EXCEPTION_CODE = 3000;
    const DATABASE_EXCEPTION_CODE = 4000;
    const EXISTS_EXCEPTION_CODE = 5000;
    const NOT_FOUND_EXCEPTION_CODE = 6000;

    /**
     * CoreException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = self::BASIC_EXCEPTION_CODE, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


    /**
     * @param string $message
     *
     * @return CoreException
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }


    /**
     * @param string $appendMessage
     * @param string $separator
     *
     * @return CoreException
     */
    public function appendToMessage(string $appendMessage, string $separator = " "): self
    {
        if (strlen($this->message) < 1) {
            $separator = "";
        }
        $this->message .= $separator . $appendMessage;
        return $this;
    }


    /**
     * @param string $prependMessage
     * @param string $separator
     *
     * @return CoreException
     */
    public function prependToMessage(string $prependMessage, string $separator = " "): self
    {
        if (strlen($this->message) < 1) {
            $separator = "";
        }
        $this->message = $prependMessage . $separator . $this->message;
        return $this;
    }
}
