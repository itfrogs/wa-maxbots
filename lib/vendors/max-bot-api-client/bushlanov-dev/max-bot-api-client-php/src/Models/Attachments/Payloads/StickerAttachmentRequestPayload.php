<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

/**
 * Payload for a sticker attachment request.
 */
final readonly class StickerAttachmentRequestPayload extends AbstractAttachmentRequestPayload
{
    /**
     * @param string $code The unique code of the sticker to be sent.
     */
    public function __construct(
        public string $code,
    ) {
    }
}
