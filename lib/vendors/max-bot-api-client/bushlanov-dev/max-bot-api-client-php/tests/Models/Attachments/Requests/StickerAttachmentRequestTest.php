<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\StickerAttachmentRequestPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Requests\StickerAttachmentRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StickerAttachmentRequest::class)]
#[UsesClass(StickerAttachmentRequestPayload::class)]
final class StickerAttachmentRequestTest extends TestCase
{
    #[Test]
    public function itCreatesCorrectRequestAndSerializesToArray(): void
    {
        $stickerCode = 'my_awesome_sticker_code';
        $request = new StickerAttachmentRequest($stickerCode);

        $this->assertInstanceOf(StickerAttachmentRequest::class, $request);
        $this->assertSame(AttachmentType::Sticker, $request->type);
        $this->assertInstanceOf(StickerAttachmentRequestPayload::class, $request->payload);
        $this->assertSame($stickerCode, $request->payload->code);

        $expectedArray = [
            'type' => 'sticker',
            'payload' => [
                'code' => $stickerCode,
            ],
        ];

        $this->assertEquals($expectedArray, $request->toArray());
    }
}
