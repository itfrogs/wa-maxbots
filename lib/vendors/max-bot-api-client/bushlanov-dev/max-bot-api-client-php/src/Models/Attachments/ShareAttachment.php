<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\ShareAttachmentRequestPayload;

/**
 * Represents a share (URL preview) attachment.
 */
final readonly class ShareAttachment extends AbstractAttachment
{
    public function __construct(
        public ShareAttachmentRequestPayload $payload,
        public ?string $title,
        public ?string $description,
        public ?string $imageUrl,
    ) {
        parent::__construct(AttachmentType::Share);
    }
}
