<?php

namespace Macellan\Twilio;

use Macellan\Twilio\Exceptions\InvalidConfigurationException;

class TwilioConfig
{
    private array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function getAccountSID(): ?string
    {
        return $this->config['account_sid'] ?? null;
    }

    public function getAuthToken(): ?string
    {
        return $this->config['auth_token'] ?? null;
    }

    public function getFrom(): ?string
    {
        return $this->config['from'] ?? null;
    }

    public function isEnable(): bool
    {
        return (bool) ($this->config['enable'] ?? false);
    }

    public function isDebug(): bool
    {
        return (bool) ($this->config['debug'] ?? false);
    }

    public function isSandboxMode(): bool
    {
        return (bool) ($this->config['sandbox_mode'] ?? false);
    }

    /**
     * @throws InvalidConfigurationException
     */
    public function checkConfig(): bool
    {
        if (! $this->getAccountSID() ||
            ! $this->getAuthToken() ||
            ! $this->getFrom()
        ) {
            throw new InvalidConfigurationException('You must set either the account_sid, auth_token and from');
        }

        return true;
    }
}
