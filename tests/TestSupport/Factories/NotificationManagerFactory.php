<?php

namespace Kirago\NotificationManager\Tests\TestSupport\Factories;

use function collect;
use Illuminate\Database\Eloquent\Factories\Factory;
use Kirago\NotificationManager\Enums\NotificationAlertType;
use Kirago\NotificationManager\Enums\NotificationPreviewType;
use Kirago\NotificationManager\Models\NotificationManager;
use Kirago\NotificationManager\Tests\TestSupport\Models\User;

class NotificationManagerFactory extends Factory
{
    protected $model = NotificationManager::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'notification' => $this->faker->randomElement(collect(config('notification-manager.subscribable_notifications'))->keys()->all()),
            'notifiable_id' => User::factory()->create()$user->getKey(),
            'notifiable_type' => 'App/User',
            'channel' => "*",
            'preview_type' => NotificationPreviewType::WHEN_UNLOCKED->value,
            'alert_type' => NotificationAlertType::NOTIFICATION_CENTER->value,
            'unsubscribed_at' => null,
            'is_prioritized' => false,
            'is_muted' => false,
        ];
    }
}
