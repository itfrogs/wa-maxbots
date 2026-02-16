<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\UserWithPhoto;

/**
 * Bot gets this type of update as soon as user pressed `Start` button.
 */
final readonly class BotStartedUpdate extends AbstractUpdate
{
    /**
     * @param int $timestamp Unix-time when event has occurred.
     * @param int $chatId Dialog identifier where event has occurred.
     * @param UserWithPhoto $user User pressed the 'Start' button.
     * @param string|null $payload Additional data from deep-link passed on bot startup.
     * @param string|null $userLocale Current user locale in IETF BCP 47 format.
     */
    public function __construct(
        int $timestamp,
        public int $chatId,
        public UserWithPhoto $user,
        public ?string $payload,
        public ?string $userLocale,
    ) {
        parent::__construct(UpdateType::BotStarted, $timestamp);
    }
}
