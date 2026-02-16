<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;

/**
 * Represents a paginated list of chats.
 */
final readonly class ChatList extends AbstractModel
{
    /**
     * @param Chat[] $chats List of requested chats.
     * @param int|null $marker Reference to the next page of requested chats. Can be null if it's the last page.
     */
    public function __construct(
        #[ArrayOf(Chat::class)]
        public array $chats,
        public ?int $marker,
    ) {
    }
}
