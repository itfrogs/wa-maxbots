<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\UserWithPhoto;

/**
 * You will receive this update when the bot has been added to a chat.
 */
final readonly class BotAddedToChatUpdate extends AbstractUpdate
{
    /**
     * @param int $timestamp Unix-time when the event has occurred.
     * @param int $chatId Chat ID where the bot was added.
     * @param UserWithPhoto $user User who added the bot to the chat.
     * @param bool $isChannel Indicates whether the bot has been added to a channel or not.
     */
    public function __construct(
        int $timestamp,
        public int $chatId,
        public UserWithPhoto $user,
        public bool $isChannel,
    ) {
        parent::__construct(UpdateType::BotAdded, $timestamp);
    }
}
