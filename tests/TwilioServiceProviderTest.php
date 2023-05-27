<?php

namespace Macellan\Twilio\Tests;

use Macellan\Twilio\Exceptions\InvalidConfigurationException;
use Macellan\Twilio\Twilio;
use Macellan\Twilio\TwilioChannel;
use Macellan\Twilio\TwilioConfig;

class TwilioServiceProviderTest extends TestCase
{
    public function test_empty_config_cannot_create_twilio_config(): void
    {
        $this->app['config']->set('services.sms.twilio', null);

        $this->expectException(InvalidConfigurationException::class);

        $this->app->get(TwilioConfig::class);
    }

    public function test_empty_credentials_cannot_create_twilio(): void
    {
        $this->app['config']->set('services.sms.twilio.account_sid', null);
        $this->app['config']->set('services.sms.twilio.auth_token', null);
        $this->app['config']->set('services.sms.twilio.from', null);

        $this->expectException(InvalidConfigurationException::class);

        $this->app->get(Twilio::class);
    }

    public function test_can_get_channel(): void
    {
        $this->assertInstanceOf(TwilioChannel::class, $this->app->get(TwilioChannel::class));
    }
}
