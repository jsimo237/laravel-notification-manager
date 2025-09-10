<?php

namespace Kirago\NotificationManager\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Kirago\NotificationManager\NotificationManagerServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Kirago\\NotificationManager\\Tests\\TestSupport\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            NotificationManagerServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $notificationManager = include __DIR__ . '/../database/migrations/create_notification_manager_table.php.stub';
        $user = include __DIR__ . '/TestSupport/Migrations/2021_12_15_101529_create_user_table.php';
        $order = include __DIR__ . '/TestSupport/Migrations/2021_12_15_101544_create_order_table.php';
        $notificationManager->up();
        $user->up();
        $order->up();

        config([
            'notification-manager.subscribable_notifications' => [
                'order.accepted' => \Kirago\NotificationManager\Tests\TestSupport\Notifications\OrderApprovedSubscribableNotification::class,
                'order.rejected' => \Kirago\NotificationManager\Tests\TestSupport\Notifications\OrderRejectedSubscribableNotification::class,
            ],
            'channels' => 'database,broadcast',
        ]);
    }
}
