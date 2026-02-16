<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\ShareAttachmentRequestPayload;

/**
 * Request to attach a media preview of an external URL.
 */
final readonly class ShareAttachmentRequest extends AbstractAttachmentRequest
{
    /**
     * Creates a request to attach a URL preview.
     *
     * @param string $url The URL to generate a preview for.
     *
     * @return ShareAttachmentRequest
     */
    public static function fromUrl(string $url): self
    {
        return new self(new ShareAttachmentRequestPayload(url: $url));
    }

    /**
     * Creates a request to re-send a URL preview using its token.
     *
     * @param string $token The token of a previously generated preview.
     *
     * @return ShareAttachmentRequest
     */
    public static function fromToken(string $token): self
    {
        return new self(new ShareAttachmentRequestPayload(token: $token));
    }

    private function __construct(ShareAttachmentRequestPayload $payload)
    {
        parent::__construct(AttachmentType::Share, $payload);
    }
}
