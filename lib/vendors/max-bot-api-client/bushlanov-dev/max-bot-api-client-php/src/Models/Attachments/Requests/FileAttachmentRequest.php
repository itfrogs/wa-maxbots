<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\UploadedInfoAttachmentRequestPayload;

/**
 * Request to attach a generic file to a message.
 */
final readonly class FileAttachmentRequest extends AbstractAttachmentRequest
{
    /**
     * @param string $token The unique token received after a successful file upload.
     */
    public function __construct(string $token)
    {
        parent::__construct(
            AttachmentType::File,
            new UploadedInfoAttachmentRequestPayload($token),
        );
    }
}
