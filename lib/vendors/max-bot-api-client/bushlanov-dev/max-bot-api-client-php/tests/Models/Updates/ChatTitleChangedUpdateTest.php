<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\Updates\ChatTitleChangedUpdate;
use BushlanovDev\MaxMessengerBot\Models\UserWithPhoto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ChatTitleChangedUpdate::class)]
#[UsesClass(UserWithPhoto::class)]
final class ChatTitleChangedUpdateTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArrayAndSerialized(): void
    {
        $data = [
            'update_type' => 'chat_title_changed',
            'timestamp' => 1680000000,
            'chat_id' => 12345,
            'user' => [
                'user_id' => 54321,
                'first_name' => 'John',
                'is_bot' => false,
                'last_activity_time' => 1679999999,
                'last_name' => null,
                'username' => null,
                'description' => null,
                'avatar_url' => null,
                'full_avatar_url' => null,
            ],
            'title' => 'New Awesome Chat Title',
        ];

        $update = ChatTitleChangedUpdate::fromArray($data);

        $this->assertInstanceOf(ChatTitleChangedUpdate::class, $update);
        $this->assertSame(UpdateType::ChatTitleChanged, $update->updateType);
        $this->assertSame(1680000000, $update->timestamp);
        $this->assertSame(12345, $update->chatId);
        $this->assertSame('New Awesome Chat Title', $update->title);
        $this->assertInstanceOf(UserWithPhoto::class, $update->user);
        $this->assertSame(54321, $update->user->userId);
        $this->assertEquals($data, $update->toArray());
    }
}
