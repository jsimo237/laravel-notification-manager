<?php

namespace Kirago\NotificationManager;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Kirago\NotificationManager\Enums\NotificationAlertType;
use Kirago\NotificationManager\Enums\NotificationPreviewType;
use Kirago\NotificationManager\Models\NotificationManager as NotificationManagerModel;

class NotificationManager
{
    public Model|Authenticatable|null $notifiable;

    public function __construct($notifiable = null)
    {
        $this->notifiable = $notifiable ?? Auth::user()->getMorphClass();
    }

    /**
     * @return $this
     */
    public function for(Model|Authenticatable $notifiable): self
    {
        $this->notifiable = $notifiable;

        return $this;
    }

    /**
     * Subscribe a user to a notification
     *
     * @param $subscribableNotificationClass
     * @param string $channel
     */
    public function subscribe($subscribableNotificationClass, string $channel = '*'): void
    {
        NotificationManagerModel::updateOrCreate([
            'notification' => $subscribableNotificationClass::subscribableNotificationType(),
            'notifiable_type' => $this->notifiable->getMorphClass(),
            'notifiable_id' => $this->notifiable->getKey(),
        ], [
            'channel' => $channel,
            'unsubscribed_at' => null,
        ]);
    }

    /**
     * Unsubscribe a user to a notification
     *
     * @param $subscribableNotificationClass
     * @param string $channel
     */
    public function unsubscribe($subscribableNotificationClass, string $channel = '*'): void
    {
        NotificationManagerModel::updateOrCreate([
            'notification' => $subscribableNotificationClass::subscribableNotificationType(),
            'notifiable_type' => $this->notifiable->getMorphClass(),
            'notifiable_id' => $this->notifiable->getKey(),
        ], [
            'channel' => $channel,
            'unsubscribed_at' => Carbon::now(),
        ]);
    }

    /**
     * Prioritize a notification for a user
     *
     * @param $subscribableNotificationClass
     */
    public function prioritize($subscribableNotificationClass): void
    {
        $this->subscribe($subscribableNotificationClass);

        NotificationManagerModel::query()
            ->where([
                'notifiable_type' => $this->notifiable->getMorphClass(),
                'notifiable_id' => $this->notifiable->getKey(),
                'notification' => $subscribableNotificationClass::subscribableNotificationType(),
            ])->update([
                'is_prioritized' => true,
            ]);
    }

    /**
     * Trivialize a notification for a user
     *
     * @param $subscribableNotificationClass
     */
    public function trivialize($subscribableNotificationClass): void
    {
        $this->subscribe($subscribableNotificationClass);

        NotificationManagerModel::query()
            ->where([
                'notifiable_type' => $this->notifiable->getMorphClass(),
                'notifiable_id' => $this->notifiable->getKey(),
                'notification' => $subscribableNotificationClass::subscribableNotificationType(),
            ])->update([
                'is_prioritized' => false,
            ]);
    }

    /**
     * Mute a notification for a user
     *
     * @param $subscribableNotificationClass
     */
    public function mute($subscribableNotificationClass): void
    {
        $this->subscribe($subscribableNotificationClass);

        NotificationManagerModel::query()
            ->where([
                'notifiable_type' => $this->notifiable->getMorphClass(),
                'notifiable_id' => $this->notifiable->getKey(),
                'notification' => $subscribableNotificationClass::subscribableNotificationType(),
            ])->update([
                'is_muted' => true,
            ]);
    }

    /**
     * Mute a notification for a user
     *
     * @param $subscribableNotificationClass
     */
    public function unmute($subscribableNotificationClass): void
    {
        $this->subscribe($subscribableNotificationClass);

        NotificationManagerModel::query()
            ->where([
                'notifiable_type' => $this->notifiable->getMorphClass(),
                'notifiable_id' => $this->notifiable->getKey(),
                'notification' => $subscribableNotificationClass::subscribableNotificationType(),
            ])->update([
                'is_muted' => false,
            ]);
    }

    /**
     * Subscribe to all notifications for a user
     *
     * @param string $channel
     */
    public function subscribeAll(string $channel = '*'): void
    {
        NotificationManagerModel::query()
            ->where([
                'notifiable_type' => $this->notifiable->getMorphClass(),
                'notifiable_id' => $this->notifiable->getKey(),
            ])
            ->update([
                'unsubscribed_at' => null,
                'channel' => $channel,
            ]);
    }

    /**
     * Unsubscribe form all notifications for a user
     *
     * @param string $channel
     */
    public function unsubscribeAll(string $channel = '*'): void
    {
        NotificationManagerModel::query()
            ->where([
                'notifiable_type' => $this->notifiable->getMorphClass(),
                'notifiable_id' => $this->notifiable->getKey(),
            ])
            ->update([
                'unsubscribed_at' => Carbon::now(),
                'channel' => $channel,
            ]);
    }

    /**
     * Update alert type for a user
     *
     * @param $subscribableNotificationClass
     * @param NotificationAlertType $notificationAlertType
     */
    public function alertType($subscribableNotificationClass, NotificationAlertType $notificationAlertType): void
    {
        $this->subscribe($subscribableNotificationClass);

        NotificationManagerModel::query()
            ->where([
                'notifiable_type' => $this->notifiable->getMorphClass(),
                'notifiable_id' => $this->notifiable->getKey(),
                'notification' => $subscribableNotificationClass::subscribableNotificationType(),
            ])->update([
                'alert_type' => $notificationAlertType->value,
            ]);
    }

    /**
     * Update preview type for a user
     *
     * @param $subscribableNotificationClass
     * @param NotificationPreviewType $notificationPreviewType
     */
    public function previewType($subscribableNotificationClass, NotificationPreviewType $notificationPreviewType): void
    {
        $this->subscribe($subscribableNotificationClass);

        NotificationManagerModel::query()
            ->where([
                'notifiable_type' => $this->notifiable->getMorphClass(),
                'notifiable_id' => $this->notifiable->getKey(),
                'notification' => $subscribableNotificationClass::subscribableNotificationType(),
            ])->update([
                'preview_type' => $notificationPreviewType->value,
            ]);
    }

    /**
     * Retrieve notification details
     *
     * @param $subscribableNotificationClass
     * @param Model $notifiable
     * @return NotificationManagerModel
     */
    public function details($subscribableNotificationClass, Model $notifiable): NotificationManagerModel
    {
        return NotificationManagerModel::where([
            'notifiable_type' => $notifiable->getMorphClass(),
            'notifiable_id' => $notifiable->getKey(),
            'notification' => $subscribableNotificationClass::subscribableNotificationType(),
        ])->first();
    }
}
