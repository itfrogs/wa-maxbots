<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Buttons\Reply;

use BushlanovDev\MaxMessengerBot\Enums\Intent;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\SendMessageButton;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(SendMessageButton::class)]
final class SendMessageButtonTest extends TestCase
{
    #[Test]
    public function toArrayWithDefaults(): void
    {
        $button = new SendMessageButton('Click Me');

        $expected = [
            'type' => 'message',
            'text' => 'Click Me',
            'payload' => null,
            'intent' => 'default',
        ];

        $this->assertEquals($expected, $button->toArray());
    }

    #[Test]
    public function toArrayWithAllParameters(): void
    {
        $button = new SendMessageButton('Confirm', 'confirm-action-123', Intent::Positive);

        $expected = [
            'type' => 'message',
            'text' => 'Confirm',
            'payload' => 'confirm-action-123',
            'intent' => 'positive',
        ];

        $this->assertEquals($expected, $button->toArray());
    }
}
