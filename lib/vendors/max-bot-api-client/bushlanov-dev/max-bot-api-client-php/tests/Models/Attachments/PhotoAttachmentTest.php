<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoAttachmentPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\PhotoAttachment;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PhotoAttachment::class)]
#[UsesClass(PhotoAttachmentPayload::class)]
final class PhotoAttachmentTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArray(): void
    {
        $data = ['type' => 'image', 'payload' => ['photo_id' => 1, 'token' => 't', 'url' => 'u']];
        $attachment = PhotoAttachment::fromArray($data);
        $this->assertInstanceOf(PhotoAttachment::class, $attachment);
        $this->assertInstanceOf(PhotoAttachmentPayload::class, $attachment->payload);
        $this->assertSame(1, $attachment->payload->photoId);
    }
}
