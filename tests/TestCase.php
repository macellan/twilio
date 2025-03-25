<?php

namespace Macellan\Twilio\Tests;

use Illuminate\Contracts\Config\Repository;
use Macellan\Twilio\TwilioServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app)
    {
        return [
            TwilioServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        tap($app->make('config'), function (Repository $config) {
            $config->set('services.sms.twilio', [
                'account_sid' => 'TEST_SID',
                'auth_token' => 'TEST_AUTH_TOKEN',
                'from' => 'TEST_FROM',
                'enable' => true,
                'debug' => true,
                'sandbox_mode' => false,
            ]);
        });
    }
}
