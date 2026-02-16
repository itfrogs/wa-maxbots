<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\Message;

/**
 * You will get this update as soon as a message is edited.
 */
final readonly class MessageEditedUpdate extends AbstractUpdate
{
    /**
     * @param int $timestamp Unix-time when the event has occurred.
     * @param Message $message The edited message.
     */
    public function __construct(
        int $timestamp,
        public Message $message,
    ) {
        parent::__construct(UpdateType::MessageEdited, $timestamp);
    }
}
