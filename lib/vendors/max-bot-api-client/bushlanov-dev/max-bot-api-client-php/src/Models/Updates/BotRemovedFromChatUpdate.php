<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\UserWithPhoto;

/**
 * You will receive this update when the bot has been removed from a chat.
 */
final readonly class BotRemovedFromChatUpdate extends AbstractUpdate
{
    /**
     * @param int $timestamp Unix-time when the event has occurred.
     * @param int $chatId Chat identifier the bot was removed from.
     * @param UserWithPhoto $user User who removed the bot from the chat.
     * @param bool $isChannel Indicates whether the bot has been removed from a channel or not.
     */
    public function __construct(
        int $timestamp,
        public int $chatId,
        public UserWithPhoto $user,
        public bool $isChannel,
    ) {
        parent::__construct(UpdateType::BotRemoved, $timestamp);
    }
}
