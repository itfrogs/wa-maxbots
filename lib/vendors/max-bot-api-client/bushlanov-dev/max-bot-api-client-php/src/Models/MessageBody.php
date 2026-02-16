<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Models\Attachments\AbstractAttachment;
use BushlanovDev\MaxMessengerBot\Models\Markup\AbstractMarkup;

/**
 * Body of created message. Text + attachments.
 */
final readonly class MessageBody extends AbstractModel
{
    /**
     * @param string $mid Unique identifier of message.
     * @param int $seq Sequence identifier of message in chat.
     * @param string|null $text Message text.
     * @param AbstractAttachment[]|null $attachments Message attachments.
     * @param AbstractMarkup[]|null $markup Message text markup.
     */
    public function __construct(
        public string $mid,
        public int $seq,
        public ?string $text,
        public ?array $attachments,
        public ?array $markup,
    ) {
    }
}
