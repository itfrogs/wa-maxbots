<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Models\Chat;
use BushlanovDev\MaxMessengerBot\Models\Image;
use BushlanovDev\MaxMessengerBot\Models\UserWithPhoto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Chat::class)]
#[CoversClass(Image::class)]
#[CoversClass(UserWithPhoto::class)]
final class ChatTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArray(): void
    {
        $data = [
            'chat_id' => 123,
            'type' => 'chat',
            'status' => 'active',
            'last_event_time' => 1678886400000,
            'participants_count' => 50,
            'is_public' => false,
            'title' => 'Test Chat',
            'icon' => [
                'url' => 'https://example.com/icon.jpg',
                'width' => 50,
                'height' => 50,
            ],
            'owner_id' => 123,
            'link' => 'https://max.ru/chat/123',
            'description' => 'This is a test chat',
            'dialog_with_user' => [
                'user_id' => 456,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'username' => 'johndoe',
                'is_bot' => false,
                'last_activity_time' => 1678886400000,
                'description' => 'Description',
                'avatar_url' => 'https://example.com/avatar.jpg',
                'full_avatar_url' => 'https://example.com/full_avatar.jpg',
            ],
            'messages_count' => 100,
            'chat_message_id' => 'mid.123',
            'participants' => [
                '456' => 1678886400000,
                '789' => 1678886500000,
            ],
        ];

        $chat = Chat::fromArray($data);

        $this->assertInstanceOf(Chat::class, $chat);
        $this->assertSame($data['chat_id'], $chat->chatId);
        $this->assertSame($data['type'], $chat->type->value);
        $this->assertSame($data['status'], $chat->status->value);
        $this->assertSame($data['last_event_time'], $chat->lastEventTime);
        $this->assertSame($data['participants_count'], $chat->participantsCount);
        $this->assertSame($data['is_public'], $chat->isPublic);
        $this->assertSame($data['title'], $chat->title);
        $this->assertSame($data['icon']['url'], $chat->icon->url);
        $this->assertSame($data['owner_id'], $chat->ownerId);
        $this->assertSame($data['link'], $chat->link);
        $this->assertSame($data['description'], $chat->description);
        $this->assertSame($data['dialog_with_user']['user_id'], $chat->dialogWithUser->userId);
        $this->assertSame($data['dialog_with_user']['full_avatar_url'], $chat->dialogWithUser->fullAvatarUrl);
        $this->assertSame($data['messages_count'], $chat->messagesCount);
        $this->assertSame($data['chat_message_id'], $chat->chatMessageId);
    }

    #[Test]
    public function canBeCreatedFromArrayWithOptionalDataNull(): void
    {
        $data = [
            'chat_id' => 123,
            'type' => 'chat',
            'status' => 'active',
            'last_event_time' => 1678886400000,
            'participants_count' => 50,
            'is_public' => true,
        ];

        $chat = Chat::fromArray($data);

        $this->assertInstanceOf(Chat::class, $chat);
        $this->assertSame($data['chat_id'], $chat->chatId);
        $this->assertSame($data['type'], $chat->type->value);
        $this->assertSame($data['status'], $chat->status->value);
        $this->assertSame($data['last_event_time'], $chat->lastEventTime);
        $this->assertSame($data['participants_count'], $chat->participantsCount);
        $this->assertSame($data['is_public'], $chat->isPublic);
        $this->assertNull($chat->title);
        $this->assertNull($chat->icon);
        $this->assertNull($chat->ownerId);
        $this->assertNull($chat->link);
        $this->assertNull($chat->description);
        $this->assertNull($chat->dialogWithUser);
        $this->assertNull($chat->messagesCount);
        $this->assertNull($chat->chatMessageId);
        $this->assertNull($chat->participants);
    }
}
