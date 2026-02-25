<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\StickerAttachmentPayload;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(StickerAttachmentPayload::class)]
final class StickerAttachmentPayloadTest extends TestCase
{
    #[Test]
    public function canBeCreatedAndSerialized(): void
    {
        $payload = new StickerAttachmentPayload('https://example.com/sticker.webp', 'sticker_code_abc');
        $this->assertSame('https://example.com/sticker.webp', $payload->url);
        $this->assertSame('sticker_code_abc', $payload->code);
        $this->assertEquals(
            ['url' => 'https://example.com/sticker.webp', 'code' => 'sticker_code_abc'],
            $payload->toArray(),
        );
    }
}
