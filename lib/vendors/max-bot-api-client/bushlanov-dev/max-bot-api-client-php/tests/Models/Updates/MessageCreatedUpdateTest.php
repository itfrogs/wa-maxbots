<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\Message;
use BushlanovDev\MaxMessengerBot\Models\MessageBody;
use BushlanovDev\MaxMessengerBot\Models\Recipient;
use BushlanovDev\MaxMessengerBot\Models\Updates\MessageCreatedUpdate;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MessageCreatedUpdate::class)]
#[UsesClass(Message::class)]
#[UsesClass(MessageBody::class)]
#[UsesClass(Recipient::class)]
final class MessageCreatedUpdateTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArray(): void
    {
        $data = [
            'update_type' => UpdateType::MessageCreated->value,
            'timestamp' => 1678886400000,
            'message' => [
                'timestamp' => 1678886400000,
                'body' => ['mid' => 'mid.123', 'seq' => 1, 'text' => 'Hello'],
                'recipient' => ['chat_type' => 'dialog', 'user_id' => 123],
            ],
            'user_locale' => 'ru-RU',
        ];

        $update = MessageCreatedUpdate::fromArray($data);

        $this->assertInstanceOf(MessageCreatedUpdate::class, $update);
        $this->assertSame(UpdateType::MessageCreated, $update->updateType);
        $this->assertInstanceOf(Message::class, $update->message);
        $this->assertSame('ru-RU', $update->userLocale);
    }
}
