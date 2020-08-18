<?php

namespace ExceptionLive\Exceptions;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ExceptionFactory
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function make()
    {
        $this->exception();
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    private function exception(): void
    {
        if ($this->response->getStatusCode() === Response::HTTP_FORBIDDEN) {
            throw Exception::invalidApiKey();
        }

        if ($this->response->getStatusCode() === Response::HTTP_UNPROCESSABLE_ENTITY) {
            throw Exception::invalidPayload();
        }

        if ($this->response->getStatusCode() === Response::HTTP_TOO_MANY_REQUESTS) {
            throw Exception::rateLimit();
        }

        if ($this->response->getStatusCode() === Response::HTTP_INTERNAL_SERVER_ERROR) {
            throw Exception::serverError();
        }

        throw Exception::generic();
    }
}
