<?php

namespace ExceptionLive\Notifications;

class RawNotification extends Notification
{
    /**
     * @var array
     */
    protected $payload;

    /**
     * @param array $payload
     * @return RawNotification
     */
    public function setPayload(array $payload)
    {
        $this->payload = $payload;

        return $this;
    }
}
