<?php

namespace ExceptionLive;

class Environment
{
    const KEY_WHITELIST = [
        'PHP_SELF',
        'argv',
        'argc',
        'GATEWAY_INTERFACE',
        'SERVER_ADDR',
        'SERVER_NAME',
        'SERVER_SOFTWARE',
        'SERVER_PROTOCOL',
        'REQUEST_METHOD',
        'REQUEST_TIME',
        'REQUEST_TIME_FLOAT',
        'QUERY_STRING',
        'DOCUMENT_ROOT',
        'HTTPS',
        'REMOTE_ADDR',
        'REMOTE_HOST',
        'REMOTE_PORT',
        'REMOTE_USER',
        'REDIRECT_REMOTE_USER',
        'SCRIPT_FILENAME',
        'SERVER_ADMIN',
        'SERVER_PORT',
        'SERVER_SIGNATURE',
        'PATH_TRANSLATED',
        'SCRIPT_NAME',
        'REQUEST_URI',
        'PHP_AUTH_DIGEST',
        'PHP_AUTH_USER',
        'PHP_AUTH_PW',
        'AUTH_TYPE',
        'PATH_INFO',
        'ORIG_PATH_INFO',
        'APP_ENV',
    ];

    /**
     * @var
     */
    protected $name = 'production';

    /**
     * @var array
     */
    protected $includeKeys = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param array $server
     * @param array $env
     */
    public function __construct(array $server = null, array $env = null)
    {
        $this->data = array_merge(
            $server ?? $_SERVER,
            $env ?? $_ENV
        );
    }

    /**
     * @return array
     */
    public function values(): array
    {
        return $this->data();
    }

    /**
     * @param string $name
     *
     * @return Environment
     */
    public function setName($name = 'production')
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  array  $keysToInclude
     * @return Environment
     */
    public function include(array $keysToInclude): self
    {
        $this->includeKeys = array_merge($this->includeKeys, $keysToInclude);

        return $this;
    }

    /**
     * @return array
     */
    private function data(): array
    {
        return array_filter($this->data, function ($key) {
            return $this->autoIncludeKey($key) || in_array($key, $this->includeKeys);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param  string  $key
     * @return bool
     */
    private function whitelistKey(string $key): bool
    {
        return in_array($key, self::KEY_WHITELIST);
    }

    /**
     * @param  string  $key
     * @return bool
     */
    private function httpKey(string $key): bool
    {
        return strpos($key, 'HTTP_') === 0;
    }

    /**
     * @param  string  $key
     * @return bool
     */
    private function autoIncludeKey(string $key): bool
    {
        return $this->whitelistKey($key) || $this->httpKey($key);
    }
}
