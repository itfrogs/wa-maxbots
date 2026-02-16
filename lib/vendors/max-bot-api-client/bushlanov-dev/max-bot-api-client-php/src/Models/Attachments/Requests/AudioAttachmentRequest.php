<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\UploadedInfoAttachmentRequestPayload;

/**
 * Request to attach an audio file to a message.
 */
final readonly class AudioAttachmentRequest extends AbstractAttachmentRequest
{
    /**
     * @param string $token The unique token received after a successful audio file upload.
     */
    public function __construct(string $token)
    {
        parent::__construct(
            AttachmentType::Audio,
            new UploadedInfoAttachmentRequestPayload($token),
        );
    }
}
