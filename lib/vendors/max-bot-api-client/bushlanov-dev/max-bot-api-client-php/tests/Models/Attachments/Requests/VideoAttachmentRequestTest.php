<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\UploadedInfoAttachmentRequestPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Requests\VideoAttachmentRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(VideoAttachmentRequest::class)]
#[UsesClass(UploadedInfoAttachmentRequestPayload::class)]
final class VideoAttachmentRequestTest extends TestCase
{
    #[Test]
    public function itCreatesCorrectRequestAndSerializesToArray(): void
    {
        $token = 'some_unique_video_upload_token_54321';

        $request = new VideoAttachmentRequest($token);

        $this->assertInstanceOf(VideoAttachmentRequest::class, $request);
        $this->assertSame(AttachmentType::Video, $request->type);
        $this->assertInstanceOf(UploadedInfoAttachmentRequestPayload::class, $request->payload);
        $this->assertSame($token, $request->payload->token);

        $expectedArray = [
            'type' => 'video',
            'payload' => [
                'token' => $token,
            ],
        ];

        $this->assertEquals($expectedArray, $request->toArray());
    }
}
