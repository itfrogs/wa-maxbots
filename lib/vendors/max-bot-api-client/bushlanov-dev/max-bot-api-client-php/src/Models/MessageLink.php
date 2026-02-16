<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Enums\MessageLinkType;

/**
 * Message link.
 */
final readonly class MessageLink extends AbstractModel
{
    /**
     * @param MessageLinkType $type Type of message link.
     * @param string $mid Message identifier of original message.
     */
    public function __construct(
        public MessageLinkType $type,
        public string $mid,
    ) {
    }
}
