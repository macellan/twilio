<?php

namespace Macellan\Twilio\Tests;

use Macellan\Twilio\Twilio;
use Macellan\Twilio\TwilioConfig;
use Macellan\Twilio\TwilioSmsMessage;
use Mockery;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Api\V2010\Account\MessageList;
use Twilio\Rest\Client;

class TwilioTest extends TestCase
{
    private Client $mockClient;

    private Twilio $twilio;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockClient = Mockery::mock(Client::class)->makePartial();
        $this->mockClient->messages = Mockery::mock(MessageList::class);

        $twilioConfig = new TwilioConfig($this->app['config']->get('services.sms.twilio'));

        $this->twilio = new Twilio($this->mockClient, $twilioConfig);
    }

    public function test_send_sms_message(): void
    {
        $message = (new TwilioSmsMessage('Test Message'))
            ->setFrom('+1234567889')
            ->setTo('+905554443322');

        $this->mockClient->messages->shouldReceive('create')
            ->andReturn(Mockery::mock(MessageInstance::class));

        $this->twilio->sendSmsMessage($message);

        $this->expectNotToPerformAssertions();
    }
}
