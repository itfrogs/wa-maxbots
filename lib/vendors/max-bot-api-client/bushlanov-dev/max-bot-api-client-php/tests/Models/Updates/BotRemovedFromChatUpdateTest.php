<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\Updates\BotRemovedFromChatUpdate;
use BushlanovDev\MaxMessengerBot\Models\UserWithPhoto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BotRemovedFromChatUpdate::class)]
#[UsesClass(UserWithPhoto::class)]
final class BotRemovedFromChatUpdateTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArrayAndSerialized(): void
    {
        $data = [
            'update_type' => 'bot_removed',
            'timestamp' => 1679100000,
            'chat_id' => 123456,
            'user' => [
                'user_id' => 555,
                'first_name' => 'Admin',
                'username' => 'chat_admin',
                'is_bot' => false,
                'last_activity_time' => 1679000000,
                'last_name' => null,
                'description' => null,
                'avatar_url' => null,
                'full_avatar_url' => null,
            ],
            'is_channel' => false,
        ];

        $update = BotRemovedFromChatUpdate::fromArray($data);

        $this->assertInstanceOf(BotRemovedFromChatUpdate::class, $update);
        $this->assertSame(UpdateType::BotRemoved, $update->updateType);
        $this->assertSame(1679100000, $update->timestamp);
        $this->assertSame(123456, $update->chatId);
        $this->assertFalse($update->isChannel);
        $this->assertInstanceOf(UserWithPhoto::class, $update->user);
        $this->assertSame(555, $update->user->userId);
        $this->assertEquals($data, $update->toArray());
    }

    #[Test]
    public function canBeCreatedForChannel(): void
    {
        $data = [
            'update_type' => 'bot_removed',
            'timestamp' => 1679100001,
            'chat_id' => 777888999,
            'user' => [
                'user_id' => 556,
                'first_name' => 'AdminChan',
                'is_bot' => false,
                'last_activity_time' => 1679000001,
            ],
            'is_channel' => true,
        ];

        $update = BotRemovedFromChatUpdate::fromArray($data);

        $this->assertInstanceOf(BotRemovedFromChatUpdate::class, $update);
        $this->assertTrue($update->isChannel);
        $this->assertSame(777888999, $update->chatId);
    }
}
