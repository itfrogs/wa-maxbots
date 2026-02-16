<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\AbstractModel;

/**
 * Payload for a sticker attachment.
 */
final readonly class StickerAttachmentPayload extends AbstractModel
{
    /**
     * @param string $url Media attachment URL.
     * @param string $code Sticker identifier.
     */
    public function __construct(
        public string $url,
        public string $code,
    ) {
    }
}
