<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\Intent;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\SendContactButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\SendMessageButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\ReplyKeyboardAttachmentRequestPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Requests\ReplyKeyboardAttachmentRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ReplyKeyboardAttachmentRequest::class)]
#[UsesClass(SendMessageButton::class)]
#[UsesClass(SendContactButton::class)]
#[UsesClass(ReplyKeyboardAttachmentRequestPayload::class)]
final class ReplyKeyboardAttachmentRequestTest extends TestCase
{
    #[Test]
    public function toArray(): void
    {
        $buttons = [
            [new SendMessageButton('Help', 'help_payload', Intent::Positive)],
            [new SendContactButton('Share Contact')],
        ];

        $request = new ReplyKeyboardAttachmentRequest(buttons: $buttons, direct: true);

        $expectedArray = [
            'type' => 'reply_keyboard',
            'payload' => [
                'buttons' => [
                    [
                        [
                            'type' => 'message',
                            'text' => 'Help',
                            'payload' => 'help_payload',
                            'intent' => 'positive',
                        ],
                    ],
                    [
                        [
                            'type' => 'user_contact',
                            'text' => 'Share Contact',
                        ],
                    ],
                ],
                'direct' => true,
                'direct_user_id' => null,
            ],
        ];

        $this->assertEquals($expectedArray, $request->toArray());
    }
}
