<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\Chat;
use BushlanovDev\MaxMessengerBot\Models\Image;
use BushlanovDev\MaxMessengerBot\Models\UserWithPhoto;
use BushlanovDev\MaxMessengerBot\Models\Updates\MessageChatCreatedUpdate;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MessageChatCreatedUpdate::class)]
#[UsesClass(Chat::class)]
#[UsesClass(UserWithPhoto::class)]
#[UsesClass(Image::class)]
final class MessageChatCreatedUpdateTest extends TestCase
{
    #[Test]
    public function canBeCreatedWithAllData(): void
    {
        $data = [
            'update_type' => 'message_chat_created',
            'timestamp' => 1683000000,
            'chat' => [
                'chat_id' => 12345,
                'type' => 'chat',
                'status' => 'active',
                'last_event_time' => 1683000000,
                'participants_count' => 1,
                'is_public' => false,
                'title' => 'New Discussion',
            ],
            'message_id' => 'mid.original.with.button',
            'start_payload' => 'payload_from_chat_button',
        ];

        $update = MessageChatCreatedUpdate::fromArray($data);

        $this->assertInstanceOf(MessageChatCreatedUpdate::class, $update);
        $this->assertSame(UpdateType::MessageChatCreated, $update->updateType);
        $this->assertInstanceOf(Chat::class, $update->chat);
        $this->assertSame(12345, $update->chat->chatId);
        $this->assertSame('mid.original.with.button', $update->messageId);
        $this->assertSame('payload_from_chat_button', $update->startPayload);
    }

    #[Test]
    public function canBeCreatedWithNullStartPayload(): void
    {
        $data = [
            'update_type' => 'message_chat_created',
            'timestamp' => 1683000001,
            'chat' => [
                'chat_id' => 54321,
                'type' => 'chat',
                'status' => 'active',
                'last_event_time' => 1683000001,
                'participants_count' => 1,
                'is_public' => false,
                'title' => 'Another Discussion',
            ],
            'message_id' => 'mid.another.message',
            'start_payload' => null,
        ];

        $update = MessageChatCreatedUpdate::fromArray($data);

        $this->assertInstanceOf(MessageChatCreatedUpdate::class, $update);
        $this->assertNull($update->startPayload);
    }
}
