<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\AbstractModel;

/**
 * Payload for a photo attachment.
 */
final readonly class PhotoAttachmentPayload extends AbstractModel
{
    /**
     * @param string $url Media attachment URL.
     * @param string $token Token to reuse the same attachment in other messages.
     * @param int $photoId Unique identifier of this image.
     */
    public function __construct(
        public string $url,
        public string $token,
        public int $photoId,
    ) {
    }
}
