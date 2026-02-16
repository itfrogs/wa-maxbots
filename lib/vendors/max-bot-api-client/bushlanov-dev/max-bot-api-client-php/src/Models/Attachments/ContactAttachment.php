<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\ContactAttachmentPayload;

final readonly class ContactAttachment extends AbstractAttachment
{
    /**
     * @param ContactAttachmentPayload $payload Contact attachment payload.
     */
    public function __construct(public ContactAttachmentPayload $payload)
    {
        parent::__construct(AttachmentType::Contact);
    }
}
