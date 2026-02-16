<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoAttachmentRequestPayload;

/**
 * Contains detailed information about a video attachment.
 */
final readonly class VideoAttachmentDetails extends AbstractModel
{
    /**
     * @param string $token The video attachment token.
     * @param int $width The width of the video in pixels.
     * @param int $height The height of the video in pixels.
     * @param int $duration The duration of the video in seconds.
     * @param VideoUrls|null $urls URLs to download or play the video. Can be null if the video is unavailable.
     * @param PhotoAttachmentRequestPayload|null $thumbnail The video's thumbnail image information.
     */
    public function __construct(
        public string $token,
        public int $width,
        public int $height,
        public int $duration,
        public ?VideoUrls $urls = null,
        public ?PhotoAttachmentRequestPayload $thumbnail = null,
    ) {
    }
}
