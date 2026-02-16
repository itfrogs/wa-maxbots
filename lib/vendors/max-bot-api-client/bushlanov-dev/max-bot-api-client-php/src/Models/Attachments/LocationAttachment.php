<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;

final readonly class LocationAttachment extends AbstractAttachment
{
    /**
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct(
        public float $latitude,
        public float $longitude,
    ) {
        parent::__construct(AttachmentType::Location);
    }
}
