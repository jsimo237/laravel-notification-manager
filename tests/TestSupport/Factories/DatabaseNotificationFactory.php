<?php

namespace Kirago\NotificationManager\Tests\TestSupport\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Kirago\NotificationManager\Enums\NotificationAlertType;
use Kirago\NotificationManager\Enums\NotificationPreviewType;
use Kirago\NotificationManager\Models\DatabaseNotification;
use Kirago\NotificationManager\Tests\TestSupport\Models\Order;
use Kirago\NotificationManager\Tests\TestSupport\Models\User;

class DatabaseNotificationFactory extends Factory
{
    protected $model = DatabaseNotification::class;

    /**
     * Default type value
     */
    private string $type = "Kirago\\NotificationManager\\Tests\\TestSupport\\Notifications\\OrderApprovedNotification.php";

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'type' => $this->type,
            'notifiable_type' => "App\\Models\\User",
            'notifiable_id' => User::factory()->create()->id,
            'data' => json_encode(Order::factory()->create()),
            'read_at' => Carbon::parse($this->faker->dateTime())->format('Y-m-d H:i:s'),
            'seen_at' => null,
            'is_prioritized' => false,
            'is_muted' => false,
            'preview_type' => NotificationPreviewType::WHEN_UNLOCKED->value,
            'alert_type' => NotificationAlertType::NOTIFICATION_CENTER->value,
        ];
    }
}
