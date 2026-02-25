<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\UploadedInfoAttachmentRequestPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Requests\AudioAttachmentRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AudioAttachmentRequest::class)]
#[UsesClass(UploadedInfoAttachmentRequestPayload::class)]
final class AudioAttachmentRequestTest extends TestCase
{
    #[Test]
    public function itCreatesCorrectRequestAndSerializesToArray(): void
    {
        $token = 'some_unique_audio_upload_token_98765';
        $request = new AudioAttachmentRequest($token);

        $this->assertInstanceOf(AudioAttachmentRequest::class, $request);
        $this->assertSame(AttachmentType::Audio, $request->type);
        $this->assertInstanceOf(UploadedInfoAttachmentRequestPayload::class, $request->payload);
        $this->assertSame($token, $request->payload->token);

        $expectedArray = [
            'type' => 'audio',
            'payload' => [
                'token' => $token,
            ],
        ];

        $this->assertEquals($expectedArray, $request->toArray());
    }
}
