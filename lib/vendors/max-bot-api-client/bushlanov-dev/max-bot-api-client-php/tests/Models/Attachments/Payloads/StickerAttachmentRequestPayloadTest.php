<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\StickerAttachmentRequestPayload;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(StickerAttachmentRequestPayload::class)]
final class StickerAttachmentRequestPayloadTest extends TestCase
{
    #[Test]
    public function itCorrectlyHandlesCreationSerializationAndState(): void
    {
        $codeValue = 'some_code_string_123';
        $rawData = ['code' => $codeValue];

        $stickerPayload = StickerAttachmentRequestPayload::fromArray($rawData);

        $this->assertInstanceOf(StickerAttachmentRequestPayload::class, $stickerPayload);
        $this->assertSame($codeValue, $stickerPayload->code);

        $serializedData = $stickerPayload->toArray();
        $this->assertEquals($rawData, $serializedData);

        $directInstance = new StickerAttachmentRequestPayload($codeValue);
        $this->assertSame($codeValue, $directInstance->code);
    }
}
