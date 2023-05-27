<?php

namespace Macellan\Twilio\Tests\Notifications;

use Illuminate\Notifications\Notification;

class TestSmsStringNotification extends Notification
{
    public function toTwilio(): string
    {
        return 'Test sms message body';
    }
}
