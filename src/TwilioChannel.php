<?php

namespace Macellan\Twilio;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Throwable;

class TwilioChannel
{
    private Twilio $twilio;

    public function __construct(Twilio $twilio)
    {
        $this->twilio = $twilio;
    }

    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (! $this->twilio->getConfig()->isEnable()) {
            $this->log('Twilio is disabled');

            return;
        }

        /** @var TwilioSmsMessage|string $message */
        $message = $notification->toTwilio($notifiable);

        if (is_string($message)) {
            $message = new TwilioSmsMessage($message);
        }
        if (! $message->getTo()) {
            $message->setTo($notifiable->routeNotificationFor('sms'));
        }

        $this->log(sprintf('Twilio sending sms - To: %s - Message: %s', $message->getTo(), print_r($message, true)));

        if ($this->twilio->getConfig()->isSandboxMode()) {
            return;
        }

        try {
            $this->twilio->sendSmsMessage($message);

            $this->log('Twilio sms sent');
        } catch (Throwable $e) {
            $this->log(sprintf(
                'Twilio message could not be sent. Error: %s',
                $e->getMessage()
            ));

            throw $e;
        }
    }

    private function log(string $message): void
    {
        if ($this->twilio->getConfig()->isDebug()) {
            Log::log('info', $message);
        }
    }
}
