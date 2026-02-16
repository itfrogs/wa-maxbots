<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\AbstractModel;

/**
 * Represents a video thumbnail image.
 */
final readonly class VideoThumbnail extends AbstractModel
{
    /**
     * @param string $url Media attachment URL.
     */
    public function __construct(public string $url)
    {
    }
}
