<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Enums\MessageLinkType;

/**
 * Represents a forwarded or replied message linked to the main message.
 */
final readonly class LinkedMessage extends AbstractModel
{
    /**
     * @param MessageLinkType $type Type of linked message (forward or reply).
     * @param MessageBody $message The body of the original message.
     * @param UserWithPhoto|null $sender The sender of the original message. Can be null if posted on behalf of a channel.
     * @param int|null $chatId The chat where the message was originally posted (for forwarded messages).
     */
    public function __construct(
        public MessageLinkType $type,
        public MessageBody $message,
        public ?UserWithPhoto $sender,
        public ?int $chatId,
    ) {
    }
}
