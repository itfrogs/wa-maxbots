<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Enums\Intent;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\SendContactButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\SendMessageButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\ReplyKeyboardAttachmentRequestPayload;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ReplyKeyboardAttachmentRequestPayload::class)]
#[UsesClass(SendMessageButton::class)]
#[UsesClass(SendContactButton::class)]
final class ReplyKeyboardAttachmentRequestPayloadTest extends TestCase
{
    #[Test]
    public function toArrayWithDefaults(): void
    {
        $buttons = [
            [new SendMessageButton('Help')],
            [new SendContactButton('Share Contact')],
        ];

        $payload = new ReplyKeyboardAttachmentRequestPayload($buttons);

        $expected = [
            'buttons' => [
                [
                    [
                        'type' => 'message',
                        'text' => 'Help',
                        'payload' => null,
                        'intent' => 'default',
                    ],
                ],
                [
                    [
                        'type' => 'user_contact',
                        'text' => 'Share Contact',
                    ],
                ],
            ],
            'direct' => false,
            'direct_user_id' => null,
        ];

        $this->assertEquals($expected, $payload->toArray());
    }

    #[Test]
    public function toArrayWithAllParameters(): void
    {
        $buttons = [
            [new SendMessageButton('Confirm', 'confirm-action', Intent::Positive)],
        ];

        $payload = new ReplyKeyboardAttachmentRequestPayload(
            buttons: $buttons,
            direct: true,
            directUserId: 987654321,
        );

        $expected = [
            'buttons' => [
                [
                    [
                        'type' => 'message',
                        'text' => 'Confirm',
                        'payload' => 'confirm-action',
                        'intent' => 'positive',
                    ],
                ],
            ],
            'direct' => true,
            'direct_user_id' => 987654321,
        ];

        $this->assertEquals($expected, $payload->toArray());
    }

    #[Test]
    public function toArrayWithEmptyButtons(): void
    {
        $payload = new ReplyKeyboardAttachmentRequestPayload(buttons: []);

        $expected = [
            'buttons' => [],
            'direct' => false,
            'direct_user_id' => null,
        ];

        $this->assertEquals($expected, $payload->toArray());
    }
}
