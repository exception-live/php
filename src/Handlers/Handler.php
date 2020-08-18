<?php

namespace ExceptionLive\Handlers;

use ExceptionLive\ExceptionLive;

abstract class Handler
{
    /**
     * @var ExceptionLive
     */
    protected $exceptionLive;

    /**
     * Handler constructor.
     *
     * @param ExceptionLive $exceptionLive
     */
    public function __construct(ExceptionLive $exceptionLive)
    {
        $this->exceptionLive = $exceptionLive;
    }

    /**
     * @return void
     */
    public abstract function register(): void;
}
