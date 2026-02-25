<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\Updates\UserRemovedFromChatUpdate;
use BushlanovDev\MaxMessengerBot\Models\UserWithPhoto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UserRemovedFromChatUpdate::class)]
#[UsesClass(UserWithPhoto::class)]
final class UserRemovedFromChatUpdateTest extends TestCase
{
    #[Test]
    public function canBeCreatedWhenRemovedByAdmin(): void
    {
        $data = [
            'update_type' => 'user_removed',
            'timestamp' => 1682000000,
            'chat_id' => 98765,
            'user' => [
                'user_id' => 111,
                'first_name' => 'Removed',
                'last_name' => 'User',
                'is_bot' => false,
                'last_activity_time' => 1681000000,
                'username' => null,
                'description' => null,
                'avatar_url' => null,
                'full_avatar_url' => null,
            ],
            'admin_id' => 222,
            'is_channel' => false,
        ];

        $update = UserRemovedFromChatUpdate::fromArray($data);

        $this->assertInstanceOf(UserRemovedFromChatUpdate::class, $update);
        $this->assertSame(UpdateType::UserRemoved, $update->updateType);
        $this->assertSame(98765, $update->chatId);
        $this->assertSame(222, $update->adminId);
        $this->assertFalse($update->isChannel);
        $this->assertInstanceOf(UserWithPhoto::class, $update->user);
        $this->assertSame(111, $update->user->userId);
        $this->assertEquals($data, $update->toArray());
    }

    #[Test]
    public function canBeCreatedWhenUserLeft(): void
    {
        $data = [
            'update_type' => 'user_removed',
            'timestamp' => 1682000001,
            'chat_id' => 112233,
            'user' => [
                'user_id' => 444,
                'first_name' => 'Self',
                'last_name' => 'Remover',
                'is_bot' => false,
                'last_activity_time' => 1681000001,
                'username' => null,
                'description' => null,
                'avatar_url' => null,
                'full_avatar_url' => null,
            ],
            'admin_id' => null,
            'is_channel' => true,
        ];

        $update = UserRemovedFromChatUpdate::fromArray($data);

        $this->assertInstanceOf(UserRemovedFromChatUpdate::class, $update);
        $this->assertNull($update->adminId);
        $this->assertTrue($update->isChannel);
        $this->assertSame(444, $update->user->userId);
        $this->assertEquals($data, $update->toArray());
    }
}
