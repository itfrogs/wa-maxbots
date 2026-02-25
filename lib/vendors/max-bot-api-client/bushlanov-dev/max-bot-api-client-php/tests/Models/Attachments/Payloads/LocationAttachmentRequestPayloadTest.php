<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\LocationAttachmentRequestPayload;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(LocationAttachmentRequestPayload::class)]
final class LocationAttachmentRequestPayloadTest extends TestCase
{
    #[Test]
    public function itConstructsAndSerializesCorrectly(): void
    {
        $latitude = 55.7558;
        $longitude = 37.6173;

        $payload = new LocationAttachmentRequestPayload($latitude, $longitude);

        $this->assertInstanceOf(LocationAttachmentRequestPayload::class, $payload);
        $this->assertSame($latitude, $payload->latitude);
        $this->assertSame($longitude, $payload->longitude);

        $expectedArray = [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];
        $this->assertEquals($expectedArray, $payload->toArray());
    }

    #[Test]
    public function itHandlesNegativeCoordinates(): void
    {
        $latitude = -34.6037;
        $longitude = -58.3816;

        $payload = new LocationAttachmentRequestPayload($latitude, $longitude);

        $this->assertSame($latitude, $payload->latitude);
        $this->assertSame($longitude, $payload->longitude);
    }
}
