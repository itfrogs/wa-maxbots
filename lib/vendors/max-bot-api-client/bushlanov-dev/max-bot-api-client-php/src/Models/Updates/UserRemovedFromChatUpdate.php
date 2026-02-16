<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\UserWithPhoto;

/**
 * You will receive this update when a user has been removed from a chat where the bot is an administrator.
 */
final readonly class UserRemovedFromChatUpdate extends AbstractUpdate
{
    /**
     * @param int $timestamp Unix-time when the event has occurred.
     * @param int $chatId Chat identifier where the event has occurred.
     * @param UserWithPhoto $user User who was removed from the chat.
     * @param int|null $adminId Administrator who removed the user from the chat.
     *                          Can be `null` if the user left the chat themselves.
     * @param bool $isChannel Indicates whether the user has been removed from a channel or not.
     */
    public function __construct(
        int $timestamp,
        public int $chatId,
        public UserWithPhoto $user,
        public ?int $adminId,
        public bool $isChannel,
    ) {
        parent::__construct(UpdateType::UserRemoved, $timestamp);
    }
}
