<?php

use Illuminate\Support\Facades\Auth;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use Kirago\NotificationManager\Facades\NotificationManager;
use Kirago\NotificationManager\Tests\TestSupport\Models\Order;
use Kirago\NotificationManager\Tests\TestSupport\Models\User;
use Kirago\NotificationManager\Tests\TestSupport\Notifications\OrderApprovedSubscribableNotification;

it('can send database notifications', function () {
    $loggedInUser = User::factory()->create();
    actingAs($loggedInUser);
    $approvedOrder = Order::factory()->state(fn () => [
        'approved' => true,
    ])->create();
    NotificationManager::subscribe(OrderApprovedSubscribableNotification::class, 'database');
    OrderApprovedSubscribableNotification::sendToSubscribers($approvedOrder);

    assertDatabaseHas('notification_managers', [
        'notifiable_type' => Auth::user()->getMorphClass(),
        'notifiable_id' => Auth::id(),
        'notification' => 'order.approved',
        'unsubscribed_at' => null,
    ]);

    assertDatabaseHas('notifications', [
        'preview_type' => 'when-unlocked',
        'alert_type' => 'notification-center',
        'is_prioritized' => false,
        'is_muted' => false,
    ]);
});
