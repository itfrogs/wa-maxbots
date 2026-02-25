<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\UploadedInfoAttachmentRequestPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Requests\FileAttachmentRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FileAttachmentRequest::class)]
#[UsesClass(UploadedInfoAttachmentRequestPayload::class)]
final class FileAttachmentRequestTest extends TestCase
{
    #[Test]
    public function itCreatesCorrectRequestAndSerializesToArray(): void
    {
        $token = 'some_generic_file_upload_token_112233';
        $request = new FileAttachmentRequest($token);

        $this->assertInstanceOf(FileAttachmentRequest::class, $request);
        $this->assertSame(AttachmentType::File, $request->type);
        $this->assertInstanceOf(UploadedInfoAttachmentRequestPayload::class, $request->payload);
        $this->assertSame($token, $request->payload->token);

        $expectedArray = [
            'type' => 'file',
            'payload' => [
                'token' => $token,
            ],
        ];

        $this->assertEquals($expectedArray, $request->toArray());
    }
}
