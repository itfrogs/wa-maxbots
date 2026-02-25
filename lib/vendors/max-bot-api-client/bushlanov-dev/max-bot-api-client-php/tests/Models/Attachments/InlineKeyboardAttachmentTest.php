<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\CallbackButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\InlineKeyboardAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\KeyboardPayload;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(InlineKeyboardAttachment::class)]
#[UsesClass(KeyboardPayload::class)]
#[UsesClass(CallbackButton::class)]
final class InlineKeyboardAttachmentTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArray(): void
    {
        $data = [
            'type' => 'inline_keyboard',
            'payload' => ['buttons' => [[['type' => 'callback', 'text' => 'Press', 'payload' => 'p']]]],
        ];
        $attachment = InlineKeyboardAttachment::fromArray($data);
        $this->assertInstanceOf(InlineKeyboardAttachment::class, $attachment);
        $this->assertInstanceOf(KeyboardPayload::class, $attachment->payload);
        $this->assertCount(1, $attachment->payload->buttons);
    }
}
