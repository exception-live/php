<?php

namespace ExceptionLive\Notifications;

use ExceptionLive\Config;

abstract class Notification
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * Notification constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function make(): array
    {
        return $this->format();
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return 'report';
    }

    /**
     * @return array
     */
    protected function format(): array
    {
        return [
            'notifier' => $this->config->notifier,
            'environment' => $this->config->environment['name'],
        ];
    }
}
