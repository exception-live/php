<?php

namespace ExceptionLive\Exceptions;

use Exception as BaseException;
use Throwable;

class Exception extends BaseException
{
    /**
     * Exception constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return Exception
     */
    public static function invalidApiKey(): self
    {
        return new static('The API key provided is invalid.');
    }

    /**
     * @return Exception
     */
    public static function invalidPayload(): self
    {
        return new static('The payload sent to Exception.Live was invalid.');
    }

    /**
     * @return Exception
     */
    public static function rateLimit(): self
    {
        return new static('You have hit your exception rate limit.');
    }

    /**
     * @return Exception
     */
    public static function serverError(): self
    {
        return new static('There was an error on our end.');
    }

    /**
     * @return Exception
     */
    public static function generic(): self
    {
        return new static('There was an error sending the payload to Exception.Live');
    }
}
