<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\AbstractReplyButton;

final readonly class ReplyKeyboardAttachmentRequestPayload extends AbstractAttachmentRequestPayload
{
    /**
     * @param AbstractReplyButton[][] $buttons Two-dimensional array of buttons.
     * @param bool $direct Applicable only for chats.
     * @param int|null $directUserId If set, reply keyboard will only be shown to this participant.
     */
    public function __construct(
        public array $buttons,
        public bool $direct = false,
        public ?int $directUserId = null,
    ) {
    }
}
