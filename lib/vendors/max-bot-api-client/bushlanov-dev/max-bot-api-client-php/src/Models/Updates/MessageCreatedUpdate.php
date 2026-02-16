<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\Message;

/**
 * You will get this `update` as soon as message is created.
 */
final readonly class MessageCreatedUpdate extends AbstractUpdate
{
    /**
     * @param int $timestamp Unix-time when event has occurred.
     * @param Message $message Newly created message.
     * @param string|null $userLocale Current user locale in IETF BCP 47 format. Available only in dialogs.
     */
    public function __construct(
        int $timestamp,
        public Message $message,
        public ?string $userLocale,
    ) {
        parent::__construct(UpdateType::MessageCreated, $timestamp);
    }
}
