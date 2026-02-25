<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Enums\MessageLinkType;
use BushlanovDev\MaxMessengerBot\Models\LinkedMessage;
use BushlanovDev\MaxMessengerBot\Models\MessageBody;
use BushlanovDev\MaxMessengerBot\Models\UserWithPhoto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LinkedMessage::class)]
#[UsesClass(MessageBody::class)]
#[UsesClass(UserWithPhoto::class)]
final class LinkedMessageTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArray(): void
    {
        $data = [
            'type' => 'reply',
            'message' => [
                'mid' => 'mid.original.123',
                'seq' => 10,
                'text' => 'This is the original message.',
                'attachments' => null,
                'markup' => null,
            ],
            'sender' => [
                'user_id' => 101,
                'first_name' => 'Original',
                'last_name' => 'Sender',
                'is_bot' => false,
                'last_activity_time' => time() - 1000,
            ],
            'chat_id' => 98765,
        ];

        $linkedMessage = LinkedMessage::fromArray($data);

        $this->assertInstanceOf(LinkedMessage::class, $linkedMessage);
        $this->assertSame(MessageLinkType::Reply, $linkedMessage->type);
        $this->assertInstanceOf(MessageBody::class, $linkedMessage->message);
        $this->assertSame('mid.original.123', $linkedMessage->message->mid);
        $this->assertInstanceOf(UserWithPhoto::class, $linkedMessage->sender);
        $this->assertSame(101, $linkedMessage->sender->userId);
        $this->assertSame(98765, $linkedMessage->chatId);
    }
}
