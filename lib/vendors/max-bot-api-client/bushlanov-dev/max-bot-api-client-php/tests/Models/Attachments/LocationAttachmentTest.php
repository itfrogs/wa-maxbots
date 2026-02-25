<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Models\Attachments\LocationAttachment;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(LocationAttachment::class)]
final class LocationAttachmentTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArray(): void
    {
        $data = ['type' => 'location', 'latitude' => 55.75, 'longitude' => 37.61];
        $attachment = LocationAttachment::fromArray($data);
        $this->assertInstanceOf(LocationAttachment::class, $attachment);
        $this->assertSame(55.75, $attachment->latitude);
    }
}
