<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;
use BushlanovDev\MaxMessengerBot\Enums\ChatAdminPermission;
use BushlanovDev\MaxMessengerBot\Models\ChatAdmin;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ChatAdmin::class)]
#[UsesClass(ArrayOf::class)]
final class ChatAdminTest extends TestCase
{
    #[Test]
    public function toArraySerializesCorrectly(): void
    {
        $admin = new ChatAdmin(123, [ChatAdminPermission::Write, ChatAdminPermission::PinMessage]);

        $expected = [
            'user_id' => 123,
            'permissions' => ['write', 'pin_message'],
            'alias' => null,
        ];

        $this->assertEquals($expected, $admin->toArray());
    }
}
