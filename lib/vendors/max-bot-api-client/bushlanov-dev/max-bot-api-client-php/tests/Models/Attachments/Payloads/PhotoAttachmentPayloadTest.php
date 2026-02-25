<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\AbstractModel;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoAttachmentPayload;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PhotoAttachmentPayload::class)]
#[UsesClass(AbstractModel::class)]
final class PhotoAttachmentPayloadTest extends TestCase
{
    #[Test]
    public function canBeCreatedAndSerialized(): void
    {
        $data = [
            'photo_id' => 987654321,
            'token' => 'some_received_photo_token',
            'url' => 'https://cdn.max.ru/photos/image.jpg'
        ];

        $payload = PhotoAttachmentPayload::fromArray($data);

        $this->assertInstanceOf(PhotoAttachmentPayload::class, $payload);
        $this->assertSame(987654321, $payload->photoId);
        $this->assertSame('some_received_photo_token', $payload->token);
        $this->assertSame('https://cdn.max.ru/photos/image.jpg', $payload->url);

        $this->assertEquals($data, $payload->toArray());
    }
}
