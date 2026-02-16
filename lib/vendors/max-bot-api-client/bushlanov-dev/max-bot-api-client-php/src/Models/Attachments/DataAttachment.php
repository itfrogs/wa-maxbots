<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;

/**
 * Represents an attachment containing a payload from a SendMessageButton.
 */
final readonly class DataAttachment extends AbstractAttachment
{
    /**
     * @param string $data The payload from the button.
     */
    public function __construct(public string $data)
    {
        parent::__construct(AttachmentType::Data);
    }
}
