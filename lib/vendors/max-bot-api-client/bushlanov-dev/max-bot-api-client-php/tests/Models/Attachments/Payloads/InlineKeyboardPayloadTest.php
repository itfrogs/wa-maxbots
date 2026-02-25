<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Enums\InlineButtonType;
use BushlanovDev\MaxMessengerBot\Enums\Intent;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\CallbackButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\LinkButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\InlineKeyboardAttachmentRequestPayload;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(InlineKeyboardAttachmentRequestPayload::class)]
#[UsesClass(CallbackButton::class)]
#[UsesClass(LinkButton::class)]
final class InlineKeyboardPayloadTest extends TestCase
{
    #[Test]
    public function constructionAndPropertyAccess(): void
    {
        $buttons = [
            [new CallbackButton('Test', 'payload')],
        ];
        $payload = new InlineKeyboardAttachmentRequestPayload($buttons);

        $this->assertInstanceOf(InlineKeyboardAttachmentRequestPayload::class, $payload);
        $this->assertSame($buttons, $payload->buttons);
    }

    #[Test]
    public function toArraySerializesCorrectly(): void
    {
        $buttons = [
            [new CallbackButton('Accept', 'accept_payload', Intent::Positive)],
            [
                new CallbackButton('Decline', 'decline_payload', Intent::Negative),
                new LinkButton('Help', 'https://example.com/help'),
            ],
        ];
        $payload = new InlineKeyboardAttachmentRequestPayload($buttons);

        $resultArray = $payload->toArray();

        $expectedArray = [
            'buttons' => [
                [
                    [
                        'type' => InlineButtonType::Callback->value,
                        'text' => 'Accept',
                        'payload' => 'accept_payload',
                        'intent' => Intent::Positive->value,
                    ],
                ],
                [
                    [
                        'type' => InlineButtonType::Callback->value,
                        'text' => 'Decline',
                        'payload' => 'decline_payload',
                        'intent' => Intent::Negative->value,
                    ],
                    [
                        'type' => InlineButtonType::Link->value,
                        'text' => 'Help',
                        'url' => 'https://example.com/help',
                    ],
                ],
            ],
        ];

        $this->assertEquals($expectedArray, $resultArray);
    }

    #[Test]
    public function toArrayHandlesEmptyButtonsArray(): void
    {
        $payload = new InlineKeyboardAttachmentRequestPayload([]);
        $resultArray = $payload->toArray();

        $expectedArray = [
            'buttons' => [],
        ];
        $this->assertEquals($expectedArray, $resultArray);
    }
}
