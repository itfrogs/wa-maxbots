<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Models\Callback;
use BushlanovDev\MaxMessengerBot\Models\UserWithPhoto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Callback::class)]
#[UsesClass(UserWithPhoto::class)]
final class CallbackTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArray(): void
    {
        $data = [
            'timestamp' => 1678886400,
            'callback_id' => 'cb.12345.abc',
            'payload' => 'button_1_pressed',
            'user' => [
                'user_id' => 101,
                'first_name' => 'Jane',
                'is_bot' => false,
                'last_activity_time' => 1678886000,
                'last_name' => null,
                'username' => null,
                'description' => null,
                'avatar_url' => null,
                'full_avatar_url' => null,
            ],
        ];

        $callback = Callback::fromArray($data);

        $this->assertInstanceOf(Callback::class, $callback);
        $this->assertSame(1678886400, $callback->timestamp);
        $this->assertSame('cb.12345.abc', $callback->callbackId);
        $this->assertSame('button_1_pressed', $callback->payload);
        $this->assertInstanceOf(UserWithPhoto::class, $callback->user);
        $this->assertSame(101, $callback->user->userId);
        $this->assertEquals($data, $callback->toArray());
    }
}
