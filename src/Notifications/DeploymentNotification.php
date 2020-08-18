<?php

namespace ExceptionLive\Notifications;

class DeploymentNotification extends Notification
{
    /**
     * @var string
     */
    protected $branch = 'master';

    /**
     * @param string $branch
     * @return $this
     */
    public function setBranch($branch = "master")
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return 'deployment';
    }

    /**
     * @return array
     */
    public function format(): array
    {
        return array_merge(parent::format(),  [
            'branch' => $this->branch,
        ]);
    }
}
