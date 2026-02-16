<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\StickerAttachmentRequestPayload;

/**
 * Request to attach a sticker to a message.
 */
final readonly class StickerAttachmentRequest extends AbstractAttachmentRequest
{
    /**
     * @param string $code The unique code of the sticker.
     */
    public function __construct(string $code)
    {
        parent::__construct(
            AttachmentType::Sticker,
            new StickerAttachmentRequestPayload($code),
        );
    }
}
