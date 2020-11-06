<?php

namespace ExceptionLive\Notifications;

use ExceptionLive\BacktraceFactory;
use ExceptionLive\Environment;
use ExceptionLive\Request;
use Throwable;
use Symfony\Component\HttpFoundation\Request as FoundationRequest;

class ExceptionNotification extends Notification
{
    /**
     * @var Throwable
     */
    private $throwable;

    /**
     * @var BacktraceFactory
     */
    private $backtrace;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var array
     */
    private $additionalParams;

    /**
     * @var Environment
     */
    private $environment;

    /**
     * @param Throwable $e
     * @param FoundationRequest|null $request
     * @param array $additionalParams
     *
     * @return $this
     */
    public function setException(Throwable $e, FoundationRequest $request = null, array $additionalParams = [])
    {
        $this->throwable = $e;
        $this->backtrace = $this->getBacktrace();
        $this->request = $this->getRequestData($request);
        $this->environment = $this->getEnvironmentData();
        $this->additionalParams = $additionalParams;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function make(): array
    {
        return $this->format();
    }

    /**
     * @return array
     */
    protected function format(): array
    {
        $data = [
            'type' => get_class($this->throwable),
            'message' => $this->throwable->getMessage(),

            'method' => $this->request->params()['method'] ?? null,

            'backtrace' => $this->backtrace->trace(),

            'environment' => $this->environment->getName(),

            'cookies' => $this->request->cookies(),

            'request' => $this->request->params()['data'] ?? [],

            'query' => $this->request->params()['query'] ?? [],

            'response' => null,

            'environment_data' => $this->environment->values(),

            'url' => $this->request->url(),
            'referrer' => $this->request->referrer(),
            'hostname' => $this->config->hostname ?? gethostname(),

            'session' => $this->request->session(),

            'headers' => $this->request->headers(),
        ];

        $data = array_merge($data, $this->additionalParams);

        return array_merge(parent::format(), $data);
    }

    /**
     * @return Environment
     */
    private function getEnvironmentData(): Environment
    {
        return (new Environment)
            ->setName($this->config->environment['name'])
            ->include($this->config->environment['include']);
    }

    /**
     * @return BacktraceFactory
     */
    private function getBacktrace(): BacktraceFactory
    {
        return new BacktraceFactory($this->throwable);
    }

    /**
     * @param FoundationRequest $request
     * @return Request
     */
    private function getRequestData(FoundationRequest $request = null): Request
    {
        return (new Request($request));
    }
}
