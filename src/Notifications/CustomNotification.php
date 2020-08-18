<?php

namespace ExceptionLive\Notifications;

class CustomNotification extends Notification
{
    /**
     * @var array
     */
    protected $payload;

    /**
     * @param array $payload
     * @return CustomNotification
     */
    public function setPayload(array $payload)
    {
        $this->payload = $payload;

        return $this;
    }
}
