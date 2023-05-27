<?php

namespace Macellan\Twilio\Tests;

use Macellan\Twilio\Tests\Notifications\TestNotifiable;
use Macellan\Twilio\Tests\Notifications\TestSmsNotification;
use Macellan\Twilio\Tests\Notifications\TestSmsStringNotification;
use Macellan\Twilio\Twilio;
use Macellan\Twilio\TwilioChannel;
use Macellan\Twilio\TwilioConfig;
use Mockery;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client;

class TwilioChannelTest extends TestCase
{
    private Client $mockClient;

    private array $config;

    public function setUp(): void
    {
        parent::setUp();

        $this->config = $this->app['config']->get('services.sms.twilio');

        $this->mockClient = Mockery::mock(Client::class)->makePartial();
    }

    public function test_send_notification(): void
    {
        $this->config['enable'] = true;
        $this->config['sandbox_mode'] = false;

        $twilio = Mockery::mock(Twilio::class, [$this->mockClient, new TwilioConfig($this->config)])
            ->makePartial();
        $twilio
            ->shouldReceive('sendSmsMessage')
            ->andReturn(Mockery::mock(MessageInstance::class));

        $channel = new TwilioChannel($twilio);

        $channel->send(new TestNotifiable(), new TestSmsNotification());

        $twilio->shouldHaveReceived('sendSmsMessage');
    }

    public function test_send_notification_string_message(): void
    {
        $this->config['enable'] = true;
        $this->config['sandbox_mode'] = false;

        $twilio = Mockery::mock(Twilio::class, [$this->mockClient, new TwilioConfig($this->config)])
            ->makePartial();
        $twilio
            ->shouldReceive('sendSmsMessage')
            ->andReturn(Mockery::mock(MessageInstance::class));

        $channel = new TwilioChannel($twilio);

        $channel->send(new TestNotifiable(), new TestSmsStringNotification());

        $twilio->shouldHaveReceived('sendSmsMessage');
    }

    public function test_can_not_send_notification_throw_exception(): void
    {
        $this->expectException(TwilioException::class);

        $twilio = Mockery::mock(Twilio::class, [$this->mockClient, new TwilioConfig($this->config)])
            ->makePartial();
        $twilio
            ->shouldReceive('sendSmsMessage')
            ->andThrows(TwilioException::class);

        $channel = new TwilioChannel($twilio);

        $channel->send(new TestNotifiable(), new TestSmsNotification());
    }

    public function test_can_not_send_notification_with_sandbox_mode(): void
    {
        $this->config['enable'] = true;
        $this->config['sandbox_mode'] = true;

        $twilio = Mockery::mock(Twilio::class, [$this->mockClient, new TwilioConfig($this->config)])
            ->makePartial();

        $channel = new TwilioChannel($twilio);

        $channel->send(new TestNotifiable(), new TestSmsNotification());

        $twilio->shouldNotHaveReceived('sendSmsMessage');
    }

    public function test_can_not_send_notification_with_disable(): void
    {
        $this->config['enable'] = false;

        $twilioConfig = new TwilioConfig($this->config);
        $twilio = new Twilio($this->mockClient, $twilioConfig);

        $channel = new TwilioChannel($twilio);

        $notification = Mockery::mock(TestSmsNotification::class);

        $channel->send(new TestNotifiable(), $notification);

        $notification->shouldNotHaveReceived('toTwilio');
    }
}
