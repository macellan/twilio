# Twilio SMS Notifications Channel for Laravel

This package makes it easy to send sms notifications using [Twilio](https://documentation.twilio.com/docs) with Laravel 8.x, 9.x, 10.x

## Contents

- [Installation](#installation)
    - [Setting up the Twilio service](#setting-up-the-Twilio-service)
- [Usage](#usage)
    - [ On-Demand Notifications](#on-demand-notifications)
- [Testing](#testing)
- [Changelog](#changelog)
- [Credits](#credits)

## Installation

You can install this package via composer:

``` bash
composer require macellan/twilio
```


### Setting up the Twilio service

Add your Twilio configs to your config/services.php:

```php
// config/services.php
...
    'sms' => [
        'twilio' => [
            'account_sid' => env('TWILIO_ACCOUNT_SID', ''),
            'auth_token' => env('TWILIO_AUTH_TOKEN', ''),
            'from' => env('TWILIO_FROM', ''),
            'enable' => env('TWILIO_ENABLE', false),
            'debug' => env('TWILIO_DEBUG', false), // Will log sending attempts and results
            'sandbox_mode' => env('TWILIO_SANDBOX_MODE', false), // Will not invoke API call
        ],
    ],
...
```


## Usage

You can use the channel in your via() method inside the notification:

```php
use Illuminate\Notifications\Notification;
use Macellan\Twilio\TwilioSmsMessage;

class TestNotification extends Notification
{
    public function via($notifiable)
    {
        return ['twilio'];
    }

    public function toTwilio(object $notifiable): TwilioSmsMessage
    {
        return new TwilioSmsMessage('Twilio test message');
    }
}
```

In your notifiable model, make sure to include a routeNotificationForSms() method, which returns a phone number.

```php
public function routeNotificationForSms()
{
    return $this->phone;
}
```


### On-Demand Notifications

Sometimes you may need to send a notification to someone who is not stored as a "user" of your application. Using the Notification::route method, you may specify ad-hoc notification routing information before sending the notification:

```php
Notification::route('sms', '+905554443322')  
            ->notify(new TestNotification());
```
## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Credits

- [Arif Demir](https://github.com/epicentre)
