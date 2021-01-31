<?php

namespace ExceptionLive;

use ExceptionLive\Exceptions\Exception;
use ExceptionLive\Handlers\ErrorHandler;
use ExceptionLive\Handlers\ExceptionHandler;
use ExceptionLive\Notifications\CustomNotification;
use ExceptionLive\Notifications\DeploymentNotification;
use ExceptionLive\Notifications\ExceptionNotification;
use ExceptionLive\Notifications\RawNotification;
use Symfony\Component\HttpFoundation\Request as FoundationRequest;
use Throwable;

class ExceptionLive
{
    /**
     * Package Version
     */
    const VERSION = '0.1.2';

    /**
     * @var string
     */
    const API_URL = "https://exception.live/";

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Config
     */
    protected $config;

    /**
     * ExceptionLive constructor.
     *
     * @param array $options
     * @param Client|null $client
     */
    public function __construct(array $options = [], Client $client = null)
    {
        $this->config = new Config($options);
        $this->client = $client ?? new Client($this->config);

        $this->initHandlers();
    }

    /**
     * @param Throwable $throwable
     * @param FoundationRequest|null $request
     * @param array $additionalParams
     *
     * @return array
     *
     * @throws Exception
     */
    public function notify(Throwable $throwable, FoundationRequest $request = null, array $additionalParams = []): array
    {
        if (! $this->shouldReport($throwable)) {
            return [];
        }

        $notification = (new ExceptionNotification($this->config))
            ->setException($throwable, $request, $additionalParams);

        return $this->client->notification($notification);
    }

    /**
     * @param array $payload
     *
     * @return array
     *
     * @throws Exception
     */
    public function customNotification(array $payload): array
    {
        if (empty($this->config['api_key'])) {
            return [];
        }

        $notification = (new CustomNotification($this->config))
            ->setPayload($payload);

        return $this->client->notification($notification);
    }

    /**
     * @param callable $callable
     *
     * @return array
     *
     * @throws Exception
     */
    public function rawNotification(callable $callable): array
    {
        if (empty($this->config['api_key'])) {
            return [];
        }

        $notification = (new RawNotification($this->config))
            ->setPayload($callable($this->config));

        return $this->client->notification($notification);
    }

    /**
     * @param string $branch
     *
     * @return array
     *
     * @throws Exception
     */
    public function deployNotification(string $branch = 'master'): array
    {
        if (empty($this->config['api_key'])) {
            return [];
        }

        $notification = (new DeploymentNotification($this->config))
            ->setBranch($branch);

        return $this->client->notification($notification);
    }

    /**
     * @return void
     */
    private function initHandlers(): void
    {
        if ($this->config->handlers['exception']) {
            (new ExceptionHandler($this))->register();
        }

        if ($this->config->handlers['error']) {
            (new ErrorHandler($this))->register();
        }
    }

    /**
     * @param  \Throwable  $throwable
     *
     * @return bool
     */
    private function excludedException(Throwable $throwable): bool
    {
        return $throwable instanceof Exception
            || in_array(
                get_class($throwable),
                $this->config->excluded_exceptions
            );
    }


    /**
     * @param  \Throwable  $throwable
     *
     * @return bool
     */
    private function shouldReport(Throwable $throwable): bool
    {
        return ! $this->excludedException($throwable)
            && $this->config->api_key !== null;
    }

    /**
     * @param string $key
     * @throws Exception
     */
    public function checkin(string $key): void
    {
        $this->client->checkin($key);
    }
}
