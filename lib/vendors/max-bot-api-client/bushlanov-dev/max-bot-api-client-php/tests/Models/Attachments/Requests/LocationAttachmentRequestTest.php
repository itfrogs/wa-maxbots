<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\LocationAttachmentRequestPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Requests\LocationAttachmentRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LocationAttachmentRequest::class)]
#[UsesClass(LocationAttachmentRequestPayload::class)]
final class LocationAttachmentRequestTest extends TestCase
{
    #[Test]
    public function itCreatesRequestWithCoordinatesAndSerializes(): void
    {
        $latitude = 55.7558;
        $longitude = 37.6173;

        $request = new LocationAttachmentRequest($latitude, $longitude);

        $this->assertInstanceOf(LocationAttachmentRequest::class, $request);
        $this->assertSame(AttachmentType::Location, $request->type);
        $this->assertInstanceOf(LocationAttachmentRequestPayload::class, $request->payload);
        $this->assertSame($latitude, $request->payload->latitude);
        $this->assertSame($longitude, $request->payload->longitude);

        $expectedArray = [
            'type' => 'location',
            'payload' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
            ],
        ];
        $this->assertEquals($expectedArray, $request->toArray());
    }
}
