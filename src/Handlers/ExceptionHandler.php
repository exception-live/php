<?php

namespace ExceptionLive\Handlers;

use ExceptionLive\Exceptions\Exception;
use Throwable;

class ExceptionHandler extends Handler
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
        $this->previousHandler = set_exception_handler([$this, 'handle']);
    }

    /**
     * @param  Throwable  $e
     * @return void
     *
     * @throws Exception
     */
    public function handle(Throwable $e): void
    {
        $this->exceptionLive->notify($e);

        if (is_callable($this->previousHandler)) {
            call_user_func($this->previousHandler, $e);
        }
    }
}
