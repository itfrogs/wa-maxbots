<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;

/**
 * You will get this update as soon as a message is removed.
 */
final readonly class MessageRemovedUpdate extends AbstractUpdate
{
    /**
     * @param int $timestamp Unix-time when the event has occurred.
     * @param string $messageId Identifier of the removed message.
     * @param int $chatId Chat identifier where the message has been deleted.
     * @param int $userId User who deleted this message.
     */
    public function __construct(
        int $timestamp,
        public string $messageId,
        public int $chatId,
        public int $userId,
    ) {
        parent::__construct(UpdateType::MessageRemoved, $timestamp);
    }
}
