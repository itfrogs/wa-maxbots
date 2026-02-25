<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Enums\InlineButtonType;
use BushlanovDev\MaxMessengerBot\Enums\Intent;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\AbstractInlineButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\CallbackButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\LinkButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\InlineKeyboardAttachmentRequestPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Requests\InlineKeyboardAttachmentRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(InlineKeyboardAttachmentRequest::class)]
#[UsesClass(InlineKeyboardAttachmentRequestPayload::class)]
#[UsesClass(AbstractInlineButton::class)]
#[UsesClass(CallbackButton::class)]
#[UsesClass(LinkButton::class)]
final class InlineKeyboardAttachmentRequestTest extends TestCase
{
    #[Test]
    public function createsCorrectRequestAndSerializesToArray(): void
    {
        $buttons = [
            [new CallbackButton('Press Me', 'cb_payload_1')],
            [new LinkButton('Docs', 'https://dev.max.ru')],
        ];
        $request = new InlineKeyboardAttachmentRequest($buttons);

        $this->assertInstanceOf(InlineKeyboardAttachmentRequest::class, $request);
        $this->assertSame(AttachmentType::InlineKeyboard, $request->type);
        $this->assertInstanceOf(InlineKeyboardAttachmentRequestPayload::class, $request->payload);
        $this->assertSame($buttons, $request->payload->buttons);

        $expectedArray = [
            'type' => 'inline_keyboard',
            'payload' => [
                'buttons' => [
                    [
                        [
                            'type' => InlineButtonType::Callback->value,
                            'text' => 'Press Me',
                            'payload' => 'cb_payload_1',
                            'intent' => null,
                        ],
                    ],
                    [
                        [
                            'type' => InlineButtonType::Link->value,
                            'text' => 'Docs',
                            'url' => 'https://dev.max.ru',
                        ],
                    ],
                ],
            ],
        ];

        $this->assertEquals($expectedArray, $request->toArray());
    }

    #[Test]
    public function handlesJaggedAndMultiButtonRows(): void
    {
        $buttons = [
            [new CallbackButton('Positive', 'ok', Intent::Positive)],
            [
                new CallbackButton('Negative', 'no', Intent::Negative),
                new LinkButton('Help', 'https://example.com/help'),
            ],
        ];
        $request = new InlineKeyboardAttachmentRequest($buttons);

        $expectedArray = [
            'type' => 'inline_keyboard',
            'payload' => [
                'buttons' => [
                    [
                        [
                            'type' => InlineButtonType::Callback->value,
                            'text' => 'Positive',
                            'payload' => 'ok',
                            'intent' => Intent::Positive->value,
                        ],
                    ],
                    [
                        [
                            'type' => InlineButtonType::Callback->value,
                            'text' => 'Negative',
                            'payload' => 'no',
                            'intent' => Intent::Negative->value,
                        ],
                        [
                            'type' => InlineButtonType::Link->value,
                            'text' => 'Help',
                            'url' => 'https://example.com/help',
                        ],
                    ],
                ],
            ],
        ];

        $this->assertEquals($expectedArray, $request->toArray());
    }

    #[Test]
    public function canBeCreatedWithEmptyButtonsArray(): void
    {
        $buttons = [];
        $request = new InlineKeyboardAttachmentRequest($buttons);

        $this->assertEmpty($request->payload->buttons);

        $expectedArray = [
            'type' => 'inline_keyboard',
            'payload' => [
                'buttons' => $buttons,
            ],
        ];
        $this->assertEquals($expectedArray, $request->toArray());
    }
}
