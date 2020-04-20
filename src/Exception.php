<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore;

/**
 * Class Exception
 * @package NiceshopsDev\NiceCore
 */
class Exception extends \Exception
{
    const BASIC_EXCEPTION_CODE = 1;
    const BADFUNCTIONCALL_EXCEPTION_CODE = 2;
    const DOMAIN_EXCEPTION_CODE = 3;
    const INVALIDARGUMENT_EXCEPTION_CODE = 4;
    const LENGTH_EXCEPTION_CODE = 5;
    const LOGIC_EXCEPTION_CODE = 6;
    const OUTOFBOUNDS_EXCEPTION_CODE = 7;
    const OUTOFRANGE_EXCEPTION_CODE = 8;
    const OVERFLOW_EXCEPTION_CODE = 9;
    const RANGE_EXCEPTION_CODE = 10;
    const RUNTIME_EXCEPTION_CODE = 11;
    const UNDERFLOW_EXCEPTION_CODE = 12;
    const UNEXPECTEDVALUE_EXCEPTION_CODE = 13;
    const BADMETHODCALL_EXCEPTION_CODE = 14;
    const API_EXCEPTION_CODE = 15;
    const LOCK_EXCEPTION_CODE = 16;
    const DATABASE_EXCEPTION_CODE = 17;
    const FILE_EXISTS_EXCEPTION_CODE = 18;
    
    
    const METHOD_NOT_FOUND = 404;
    
    
    /**
     * @param string $message
     *
     * @return Exception
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
     * @return Exception
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
     * @return Exception
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