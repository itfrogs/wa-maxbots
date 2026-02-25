<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Buttons\Reply;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\SendGeoLocationButton;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(SendGeoLocationButton::class)]
final class SendGeoLocationButtonTest extends TestCase
{
    #[Test]
    public function toArrayWithDefaultQuick(): void
    {
        $button = new SendGeoLocationButton('Share Location');

        $expected = [
            'type' => 'user_geo_location',
            'text' => 'Share Location',
            'quick' => false,
        ];

        $this->assertEquals($expected, $button->toArray());
    }

    #[Test]
    public function toArrayWithQuickTrue(): void
    {
        $button = new SendGeoLocationButton('Quick Share', true);

        $expected = [
            'type' => 'user_geo_location',
            'text' => 'Quick Share',
            'quick' => true,
        ];

        $this->assertEquals($expected, $button->toArray());
    }
}
