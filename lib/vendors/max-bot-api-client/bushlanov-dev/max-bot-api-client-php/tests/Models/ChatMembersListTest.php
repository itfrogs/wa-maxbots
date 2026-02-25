<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;
use BushlanovDev\MaxMessengerBot\Models\ChatMember;
use BushlanovDev\MaxMessengerBot\Models\ChatMembersList;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ChatMembersList::class)]
#[UsesClass(ChatMember::class)]
#[UsesClass(ArrayOf::class)]
final class ChatMembersListTest extends TestCase
{
    #[Test]
    public function canBeCreatedWithData(): void
    {
        $data = [
            'members' => [
                [
                    'user_id' => 101,
                    'first_name' => 'Admin1',
                    'is_bot' => false,
                    'last_activity_time' => 1,
                    'last_access_time' => 2,
                    'is_owner' => true,
                    'is_admin' => true,
                    'join_time' => 0,
                ],
            ],
            'marker' => 12345,
        ];

        $list = ChatMembersList::fromArray($data);

        $this->assertInstanceOf(ChatMembersList::class, $list);
        $this->assertCount(1, $list->members);
        $this->assertInstanceOf(ChatMember::class, $list->members[0]);
        $this->assertSame(101, $list->members[0]->userId);
        $this->assertSame(12345, $list->marker);
    }

    #[Test]
    public function canBeCreatedWithEmptyMembersAndNullMarker(): void
    {
        $data = ['members' => [], 'marker' => null];
        $list = ChatMembersList::fromArray($data);

        $this->assertIsArray($list->members);
        $this->assertEmpty($list->members);
        $this->assertNull($list->marker);
    }
}
