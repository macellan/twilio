<?php

namespace Macellan\Twilio;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Macellan\Twilio\Exceptions\InvalidConfigurationException;
use Twilio\Rest\Client;

class TwilioServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->app->bind(TwilioConfig::class, function () {
            $config = config('services.sms.twilio');
            if (empty($config) || ! is_array($config)) {
                throw new InvalidConfigurationException('You must set services.sms.twilio');
            }

            return new TwilioConfig($config);
        });

        $this->app->singleton(Twilio::class, function (Application $app) {
            /** @var TwilioConfig $configService */
            $configService = $app->make(TwilioConfig::class);
            $configService->checkConfig();

            return new Twilio(
                new Client($configService->getAccountSID(), $configService->getAuthToken()),
                $configService
            );
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('twilio', function () {
                return new TwilioChannel($this->app->make(Twilio::class));
            });
        });
    }
}
