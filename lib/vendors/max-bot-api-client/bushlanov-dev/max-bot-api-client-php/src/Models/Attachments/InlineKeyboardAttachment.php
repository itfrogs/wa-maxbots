<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\KeyboardPayload;

final readonly class InlineKeyboardAttachment extends AbstractAttachment
{
    /**
     * @param KeyboardPayload $payload Keyboard payload.
     */
    public function __construct(public KeyboardPayload $payload)
    {
        parent::__construct(AttachmentType::InlineKeyboard);
    }
}
