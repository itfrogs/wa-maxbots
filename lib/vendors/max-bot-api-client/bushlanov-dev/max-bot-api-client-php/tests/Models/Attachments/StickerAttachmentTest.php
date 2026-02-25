<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\StickerAttachmentPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\StickerAttachment;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StickerAttachment::class)]
#[UsesClass(StickerAttachmentPayload::class)]
final class StickerAttachmentTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArray(): void
    {
        $data = ['type' => 'sticker', 'payload' => ['url' => 'u', 'code' => 'c'], 'width' => 128, 'height' => 128];
        $attachment = StickerAttachment::fromArray($data);
        $this->assertInstanceOf(StickerAttachment::class, $attachment);
        $this->assertSame(128, $attachment->width);
        $this->assertSame('c', $attachment->payload->code);
    }
}
