<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\UploadedInfoAttachmentRequestPayload;

/**
 * Request to attach a video to a message.
 */
final readonly class VideoAttachmentRequest extends AbstractAttachmentRequest
{
    /**
     * @param string $token The unique token received after a successful video upload.
     */
    public function __construct(string $token)
    {
        parent::__construct(
            AttachmentType::Video,
            new UploadedInfoAttachmentRequestPayload($token),
        );
    }
}
