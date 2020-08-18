<?php

namespace ExceptionLive;

use Symfony\Component\HttpFoundation\Request as FoundationRequest;

class Request
{
    /**
     * @var FoundationRequest
     */
    protected $request;

    /**
     * @param FoundationRequest $request
     */
    public function __construct(FoundationRequest $request = null)
    {
        $this->request = $request ?? FoundationRequest::createFromGlobals();
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return $this->httpRequest()
            ? $this->request->getUri()
            : '';
    }

    /**
     * @return array
     */
    public function params(): array
    {
        if (! $this->httpRequest()) {
            return [];
        }

        return [
            'method' => $this->request->getMethod(),
            'query' => $this->request->query->all(),
            'data' => $this->data(),
        ];
    }

    /**
     * @return array
     */
    public function session(): array
    {
        return $this->request->hasSession() && $this->request->getSession()
            ? $this->request->getSession()->all()
            : [];
    }

    /**
     * @return bool
     */
    private function httpRequest(): bool
    {
        return isset($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return array
     */
    private function data(): array
    {
        if ($this->request->getContentType() === 'json') {
            return json_decode($this->request->getContent(), true) ?: [];
        }

        if ($this->request->getContentType() === 'form') {
            return $this->request->request->all();
        }

        return [];
    }
}
