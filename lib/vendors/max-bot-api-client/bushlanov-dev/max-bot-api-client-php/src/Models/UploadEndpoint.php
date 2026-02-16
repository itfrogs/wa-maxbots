<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

/**
 * Endpoint you should upload to your binaries
 */
final readonly class UploadEndpoint extends AbstractModel
{
    /**
     * @param string $url URL to upload.
     * @param string|null $token Video or audio token for send message.
     */
    public function __construct(
        public string $url,
        public ?string $token = null,
    ) {
    }
}
