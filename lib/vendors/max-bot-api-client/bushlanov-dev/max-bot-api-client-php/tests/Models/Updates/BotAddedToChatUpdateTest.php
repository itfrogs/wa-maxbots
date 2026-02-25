<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\Updates\BotAddedToChatUpdate;
use BushlanovDev\MaxMessengerBot\Models\UserWithPhoto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BotAddedToChatUpdate::class)]
#[UsesClass(UserWithPhoto::class)]
final class BotAddedToChatUpdateTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArrayAndSerialized(): void
    {
        $data = [
            'update_type' => 'bot_added',
            'timestamp' => 1679000000,
            'chat_id' => 987654321,
            'user' => [
                'user_id' => 101,
                'first_name' => 'Admin',
                'is_bot' => false,
                'last_activity_time' => 1678000000,
                'last_name' => null,
                'username' => null,
                'description' => null,
                'avatar_url' => null,
                'full_avatar_url' => null,
            ],
            'is_channel' => false,
        ];

        $update = BotAddedToChatUpdate::fromArray($data);

        $this->assertInstanceOf(BotAddedToChatUpdate::class, $update);
        $this->assertSame(UpdateType::BotAdded, $update->updateType);
        $this->assertSame(1679000000, $update->timestamp);
        $this->assertSame(987654321, $update->chatId);
        $this->assertFalse($update->isChannel);
        $this->assertInstanceOf(UserWithPhoto::class, $update->user);
        $this->assertSame(101, $update->user->userId);
        $this->assertEquals($data, $update->toArray());
    }

    #[Test]
    public function canBeCreatedForChannel(): void
    {
        $data = [
            'update_type' => 'bot_added',
            'timestamp' => 1679000001,
            'chat_id' => 111222333,
            'user' => [
                'user_id' => 102,
                'first_name' => 'ChannelCreator',
                'is_bot' => false,
                'last_activity_time' => 1678000001,
            ],
            'is_channel' => true,
        ];

        $update = BotAddedToChatUpdate::fromArray($data);

        $this->assertInstanceOf(BotAddedToChatUpdate::class, $update);
        $this->assertTrue($update->isChannel);
        $this->assertSame(111222333, $update->chatId);
    }
}
