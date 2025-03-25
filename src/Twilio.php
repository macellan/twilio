<?php

namespace Macellan\Twilio;

use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client;

class Twilio
{
    private Client $client;

    private TwilioConfig $config;

    public function __construct(Client $client, TwilioConfig $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * @throws TwilioException
     */
    public function sendSmsMessage(TwilioSmsMessage $smsMessage): MessageInstance
    {
        return $this->client->messages->create(
            (string) $smsMessage->getTo(),
            [
                'body' => $smsMessage->getBody(),
                'from' => $smsMessage->getFrom() ?? $this->config->getFrom(),
            ],
        );
    }

    public function getConfig(): TwilioConfig
    {
        return $this->config;
    }
}
