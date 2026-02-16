<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\StickerAttachmentPayload;

final readonly class StickerAttachment extends AbstractAttachment
{
    /**
     * @param StickerAttachmentPayload $payload Sticker attachment payload.
     * @param int $width
     * @param int $height
     */
    public function __construct(
        public StickerAttachmentPayload $payload,
        public int $width,
        public int $height,
    ) {
        parent::__construct(AttachmentType::Sticker);
    }
}
