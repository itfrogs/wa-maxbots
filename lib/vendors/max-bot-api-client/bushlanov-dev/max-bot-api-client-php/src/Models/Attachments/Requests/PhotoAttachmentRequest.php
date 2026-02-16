<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoAttachmentRequestPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoToken;

/**
 * Request to attach some data to message.
 */
final readonly class PhotoAttachmentRequest extends AbstractAttachmentRequest
{
    /**
     * Creates a request to attach an image by URL.
     *
     * @param string $url
     *
     * @return PhotoAttachmentRequest
     */
    public static function fromUrl(string $url): self
    {
        return new self(new PhotoAttachmentRequestPayload(url: $url));
    }

    /**
     * Creates a request to attach an image using the token received after uploading.
     *
     * @param string $token
     *
     * @return PhotoAttachmentRequest
     */
    public static function fromToken(string $token): self
    {
        return new self(new PhotoAttachmentRequestPayload(token: $token));
    }

    /**
     * Creates a request to attach an image using the tokens received after uploading.
     *
     * @param PhotoToken[] $photos
     *
     * @return PhotoAttachmentRequest
     */
    public static function fromPhotos(array $photos): self
    {
        return new self(new PhotoAttachmentRequestPayload(photos: $photos));
    }

    /**
     * @param PhotoAttachmentRequestPayload $payload Request to attach image.
     */
    private function __construct(PhotoAttachmentRequestPayload $payload)
    {
        parent::__construct(AttachmentType::Image, $payload);
    }
}
