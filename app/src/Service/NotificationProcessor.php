<?php

namespace App\Service;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;

class NotificationProcessor
{
    public function __construct(
        private array $channels,
        private NotifierInterface $notifier
    )
    {
    }

    public function notify(string $message)
    {
        $notification = $this->buildNotification();
        $notification->content($message);
        $this->notifier->send($notification);
    }

    private function buildNotification(): Notification
    {
        return new Notification("Problems with files", $this->channels);
    }
}