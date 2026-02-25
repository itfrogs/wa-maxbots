<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Buttons\Reply;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\SendContactButton;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(SendContactButton::class)]
final class SendContactButtonTest extends TestCase
{
    #[Test]
    public function toArray(): void
    {
        $button = new SendContactButton('Share My Contact');

        $expected = [
            'type' => 'user_contact',
            'text' => 'Share My Contact',
        ];

        $this->assertEquals($expected, $button->toArray());
    }
}
