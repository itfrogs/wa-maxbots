<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments;

use BushlanovDev\MaxMessengerBot\ModelFactory;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\AbstractReplyButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\SendContactButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\SendMessageButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\ReplyKeyboardAttachment;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ReplyKeyboardAttachment::class)]
#[UsesClass(AbstractReplyButton::class)]
#[UsesClass(SendContactButton::class)]
#[UsesClass(SendMessageButton::class)]
#[UsesClass(ModelFactory::class)]
final class ReplyKeyboardAttachmentTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArrayViaFactory(): void
    {
        $data = [
            'type' => 'reply_keyboard',
            'buttons' => [
                [['type' => 'message', 'text' => 'Hello']],
                [['type' => 'user_contact', 'text' => 'My Contact']]
            ]
        ];

        $factory = new ModelFactory();
        $attachment = $factory->createAttachment($data);

        $this->assertInstanceOf(ReplyKeyboardAttachment::class, $attachment);
        $this->assertCount(2, $attachment->buttons);
        $this->assertInstanceOf(SendContactButton::class, $attachment->buttons[1][0]);
        $this->assertSame('My Contact', $attachment->buttons[1][0]->text);
    }
}
