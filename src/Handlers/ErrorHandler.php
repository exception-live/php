<?php

namespace ExceptionLive\Handlers;

use ErrorException;
use ExceptionLive\Exceptions\Exception;

class ErrorHandler extends Handler
{
    /**
     * @var callable
     */
    protected $previousHandler;

    /**
     * @return void
     */
    public function register(): void
    {
        $this->previousHandler = set_error_handler([$this, 'handle']);
    }

    /**
     * @param  int  $code
     * @param  string  $error
     * @param  string  $file
     * @param  int  $line
     * @return mixed
     *
     * @throws Exception
     */
    public function handle($code, $error, $file = null, $line = null)
    {
        if (error_reporting() === 0) {
            return false;
        }

        $this->exceptionLive->notify(
            new ErrorException($error, 0, $code, $file, $line)
        );

        if (is_callable($this->previousHandler)) {
            call_user_func($this->previousHandler, $code, $error, $file, $line);
        }
    }
}
