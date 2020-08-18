<?php

namespace ExceptionLive;

use ArrayAccess;

class Config implements ArrayAccess
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $defaults = [
        'api_key' => null,
        'notifier' => [
            'name' => 'Exception.Live PHP',
            'url' => 'https://github.com/exception-live/php',
            'version' => ExceptionLive::VERSION,
        ],

        'environment' => [
            'name' => 'production',
            'include' => [],
        ],

        'project_root' => '',

        'hostname' => null,

        'excluded_exceptions' => [],

        'handlers' => [
            'exception' => true,
            'error' => true,
        ],

        'client' => [
            'timeout' => 0,
            'proxy' => [],
        ],
    ];

    /**
     * Config constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->defaults, $options);
    }

    /**
     * @param $name
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return isset($this->options[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->options[$offset] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        if(is_null($offset)) {
            return;
        }

        $this->options[$offset] = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        //
    }
}
