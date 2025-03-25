<?php

namespace Macellan\Twilio\Tests\Notifications;

use Illuminate\Notifications\Notification;
use Macellan\Twilio\TwilioSmsMessage;

class TestSmsNotification extends Notification
{
    public function toTwilio(): TwilioSmsMessage
    {
        return new TwilioSmsMessage('Test sms message body');
    }
}
