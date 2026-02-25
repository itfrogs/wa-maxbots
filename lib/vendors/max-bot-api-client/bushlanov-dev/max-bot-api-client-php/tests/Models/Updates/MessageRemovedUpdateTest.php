<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\Updates\MessageRemovedUpdate;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(MessageRemovedUpdate::class)]
final class MessageRemovedUpdateTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArrayAndSerializedBack(): void
    {
        $data = [
            'update_type' => 'message_removed',
            'timestamp' => 1678888000,
            'message_id' => 'mid.was.here.now.gone',
            'chat_id' => 123456789,
            'user_id' => 98765,
        ];

        $update = MessageRemovedUpdate::fromArray($data);

        $this->assertInstanceOf(MessageRemovedUpdate::class, $update);
        $this->assertSame(UpdateType::MessageRemoved, $update->updateType);
        $this->assertSame(1678888000, $update->timestamp);
        $this->assertSame('mid.was.here.now.gone', $update->messageId);
        $this->assertSame(123456789, $update->chatId);
        $this->assertSame(98765, $update->userId);
        $this->assertEquals($data, $update->toArray());
    }
}
