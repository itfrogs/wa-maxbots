<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;
use BushlanovDev\MaxMessengerBot\Enums\ChatAdminPermission;
use BushlanovDev\MaxMessengerBot\Models\ChatMember;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ChatMember::class)]
#[UsesClass(ArrayOf::class)]
final class ChatMemberTest extends TestCase
{
    #[Test]
    public function canBeCreatedForAdmin(): void
    {
        $data = [
            'user_id' => 101,
            'first_name' => 'AdminBot',
            'last_name' => null,
            'username' => 'admin_bot',
            'is_bot' => true,
            'last_activity_time' => 1678886400,
            'description' => 'I am a bot.',
            'avatar_url' => null,
            'full_avatar_url' => null,
            'last_access_time' => 1679000000,
            'is_owner' => false,
            'is_admin' => true,
            'join_time' => 1678000000,
            'permissions' => ['pin_message', 'write'],
            'alias' => null,
        ];

        $member = ChatMember::fromArray($data);

        $this->assertInstanceOf(ChatMember::class, $member);
        $this->assertTrue($member->isAdmin);
        $this->assertFalse($member->isOwner);
        $this->assertIsArray($member->permissions);
        $this->assertCount(2, $member->permissions);
        $this->assertSame(ChatAdminPermission::PinMessage, $member->permissions[0]);
        $this->assertSame(ChatAdminPermission::Write, $member->permissions[1]);
        $this->assertEquals($data, $member->toArray());
    }

    #[Test]
    public function canBeCreatedForRegularMember(): void
    {
        $data = [
            'user_id' => 102,
            'first_name' => 'RegularUser',
            'last_name' => 'Smith',
            'username' => 'regular_user',
            'is_bot' => false,
            'last_activity_time' => 1678886401,
            'description' => null,
            'avatar_url' => 'http://example.com/avatar.png',
            'full_avatar_url' => 'http://example.com/avatar_full.png',
            'last_access_time' => 1679000001,
            'is_owner' => false,
            'is_admin' => false,
            'join_time' => 1678000001,
            'permissions' => null,
            'alias' => null,
        ];

        $member = ChatMember::fromArray($data);

        $this->assertFalse($member->isAdmin);
        $this->assertNull($member->permissions);
        $this->assertEquals($data, $member->toArray());
    }
}
