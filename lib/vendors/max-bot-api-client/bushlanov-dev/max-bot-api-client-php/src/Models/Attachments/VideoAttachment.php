<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\MediaAttachmentPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\VideoThumbnail;

final readonly class VideoAttachment extends AbstractAttachment
{
    /**
     * @param MediaAttachmentPayload $payload Video attachment payload.
     * @param VideoThumbnail|null $thumbnail Video thumbnail.
     * @param int|null $width Video width.
     * @param int|null $height Video height.
     * @param int|null $duration Video duration in seconds.
     */
    public function __construct(
        public MediaAttachmentPayload $payload,
        public ?VideoThumbnail $thumbnail,
        public ?int $width,
        public ?int $height,
        public ?int $duration,
    ) {
        parent::__construct(AttachmentType::Video);
    }
}
