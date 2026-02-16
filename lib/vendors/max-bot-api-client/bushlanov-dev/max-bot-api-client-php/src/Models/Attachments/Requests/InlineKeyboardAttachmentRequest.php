<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\AbstractInlineButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\InlineKeyboardAttachmentRequestPayload;

final readonly class InlineKeyboardAttachmentRequest extends AbstractAttachmentRequest
{
    /**
     * @param AbstractInlineButton[][] $buttons
     */
    public function __construct(array $buttons)
    {
        parent::__construct(
            AttachmentType::InlineKeyboard,
            new InlineKeyboardAttachmentRequestPayload($buttons),
        );
    }
}
