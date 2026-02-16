<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\LocationAttachmentRequestPayload;

/**
 * Request to attach a geographical location to a message.
 */
final readonly class LocationAttachmentRequest extends AbstractAttachmentRequest
{
    /**
     * @param float $latitude Latitude as a floating-point number.
     * @param float $longitude Longitude as a floating-point number.
     */
    public function __construct(float $latitude, float $longitude)
    {
        parent::__construct(
            AttachmentType::Location,
            new LocationAttachmentRequestPayload($latitude, $longitude),
        );
    }
}
