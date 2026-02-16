<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\AbstractModel;

/**
 * Payload for media attachments like audio or video.
 */
final readonly class MediaAttachmentPayload extends AbstractModel
{
    /**
     * @param string $url Media attachment URL.
     * @param string $token Token to reuse the same attachment in other messages.
     */
    public function __construct(
        public string $url,
        public string $token,
    ) {
    }
}
