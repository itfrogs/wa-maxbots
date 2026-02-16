<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\AbstractModel;

/**
 * Encoded information of uploaded image
 */
final readonly class PhotoToken extends AbstractModel
{
    /**
     * @param string $token Encoded information of uploaded image.
     */
    public function __construct(public string $token)
    {
    }
}
