<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\ModelFactory;
use BushlanovDev\MaxMessengerBot\Models\Attachments\AbstractAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\AbstractInlineButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\CallbackButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\LinkButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\InlineKeyboardAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\KeyboardPayload;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(KeyboardPayload::class)]
#[UsesClass(ModelFactory::class)]
#[UsesClass(AbstractAttachment::class)]
#[UsesClass(AbstractInlineButton::class)]
#[UsesClass(CallbackButton::class)]
#[UsesClass(LinkButton::class)]
#[UsesClass(InlineKeyboardAttachment::class)]
final class KeyboardPayloadTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArrayViaFactory(): void
    {
        $data = [
            'type' => 'inline_keyboard',
            'payload' => [
                'buttons' => [
                    [
                        ['type' => 'callback', 'text' => 'Press Me', 'payload' => 'payload_123'],
                    ],
                    [
                        ['type' => 'link', 'text' => 'Docs', 'url' => 'https://dev.max.ru'],
                        ['type' => 'callback', 'text' => 'Cancel', 'payload' => 'cancel_op'],
                    ],
                ]
            ]
        ];

        $factory = new ModelFactory();
        $attachment = $factory->createAttachment($data);

        $this->assertInstanceOf(InlineKeyboardAttachment::class, $attachment);
        $this->assertInstanceOf(KeyboardPayload::class, $attachment->payload);

        $buttons = $attachment->payload->buttons;
        $this->assertCount(2, $buttons);

        $this->assertCount(1, $buttons[0]);
        $this->assertInstanceOf(CallbackButton::class, $buttons[0][0]);
        $this->assertSame('payload_123', $buttons[0][0]->payload);

        $this->assertCount(2, $buttons[1]);
        $this->assertInstanceOf(LinkButton::class, $buttons[1][0]);
        $this->assertSame('https://dev.max.ru', $buttons[1][0]->url);
        $this->assertInstanceOf(CallbackButton::class, $buttons[1][1]);
        $this->assertSame('cancel_op', $buttons[1][1]->payload);
    }
}
