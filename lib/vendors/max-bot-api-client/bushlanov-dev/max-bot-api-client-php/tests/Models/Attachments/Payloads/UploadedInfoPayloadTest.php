<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\UploadedInfoAttachmentRequestPayload;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(UploadedInfoAttachmentRequestPayload::class)]
final class UploadedInfoPayloadTest extends TestCase
{
    #[Test]
    public function itCorrectlyHandlesCreationSerializationAndState(): void
    {
        $tokenValue = 'some_unique_token_string_123';
        $rawData = ['token' => $tokenValue];

        $uploadedInfoPayload = UploadedInfoAttachmentRequestPayload::fromArray($rawData);

        $this->assertInstanceOf(UploadedInfoAttachmentRequestPayload::class, $uploadedInfoPayload);
        $this->assertSame($tokenValue, $uploadedInfoPayload->token);

        $serializedData = $uploadedInfoPayload->toArray();
        $this->assertEquals($rawData, $serializedData);

        $directInstance = new UploadedInfoAttachmentRequestPayload($tokenValue);
        $this->assertSame($tokenValue, $directInstance->token);
    }
}
