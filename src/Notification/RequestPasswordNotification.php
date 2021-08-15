<?php

namespace App\Notification;

class RequestPasswordNotification
{
    private $content;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function getContent(): array
    {
        return $this->content;
    }
}