<?php

namespace Kirago\NotificationManager\Tests\TestSupport\Notifications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Kirago\NotificationManager\Contracts\SubscribableNotificationContract;
use Kirago\NotificationManager\Traits\SubscribableNotification;

class OrderApprovedSubscribableNotification extends Notification implements SubscribableNotificationContract
{
    use SubscribableNotification;

    protected Model $payload;

    /**
     * Create a new notification instance.
     */
    public function __construct(Model $payload)
    {
        $this->payload = $payload;
    }

    public static function subscribableNotificationType(): string
    {
        return 'order.approved';
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->payload->toArray();
    }
}
