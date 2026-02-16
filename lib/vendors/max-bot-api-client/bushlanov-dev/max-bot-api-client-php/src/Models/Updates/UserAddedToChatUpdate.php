<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\UserWithPhoto;

/**
 * You will receive this update when a user has been added to a chat where the bot is an administrator.
 */
final readonly class UserAddedToChatUpdate extends AbstractUpdate
{
    /**
     * @param int $timestamp Unix-time when the event has occurred.
     * @param int $chatId Chat identifier where the event has occurred.
     * @param UserWithPhoto $user User who was added to the chat.
     * @param int|null $inviterId User who added the new user to the chat.
     *                            Can be `null` if the user joined via a link.
     * @param bool $isChannel Indicates whether the user has been added to a channel or not.
     */
    public function __construct(
        int $timestamp,
        public int $chatId,
        public UserWithPhoto $user,
        public ?int $inviterId,
        public bool $isChannel,
    ) {
        parent::__construct(UpdateType::UserAdded, $timestamp);
    }
}
