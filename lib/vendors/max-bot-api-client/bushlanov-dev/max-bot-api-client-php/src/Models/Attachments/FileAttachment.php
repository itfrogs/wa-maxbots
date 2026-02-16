<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\FileAttachmentPayload;

final readonly class FileAttachment extends AbstractAttachment
{
    /**
     * @param FileAttachmentPayload $payload File attachment payload.
     * @param string $filename Uploaded file name.
     * @param int $size File size in bytes.
     */
    public function __construct(
        public FileAttachmentPayload $payload,
        public string $filename,
        public int $size,
    ) {
        parent::__construct(AttachmentType::File);
    }
}
