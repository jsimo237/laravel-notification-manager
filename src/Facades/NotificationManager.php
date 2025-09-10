<?php

namespace Kirago\NotificationManager\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Kirago\NotificationManager\Enums\NotificationAlertType;
use Kirago\NotificationManager\Enums\NotificationPreviewType;

/**
 * @see \Kirago\NotificationManager\NotificationManager
 *
 * @method static subscribableNotifications(): array
 * @method static for (Model $model): void
 * @method static subscribe($subscribableNotificationClass, string $channel = '*'): void
 * @method static unsubscribe($subscribableNotificationClass, string $channel = '*'): void
 * @method static prioritize($subscribableNotificationClass): void
 * @method static trivialize($subscribableNotificationClass): void
 * @method static mute($subscribableNotificationClass): void
 * @method static unmute($subscribableNotificationClass): void
 * @method static subscribeAll(string $channel = '*'): void
 * @method static unsubscribeAll(string $channel = '*'): void
 * @method static previewType($subscribableNotificationClass, NotificationPreviewType $notificationPreviewType): void
 * @method static alertType($subscribableNotificationClass, NotificationAlertType $notificationAlertType): void
 * @method static details($subscribableNotificationClass, Model $notifiable): NotificationManagerModel
 */
class NotificationManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Kirago\NotificationManager\NotificationManager::class;
    }
}
