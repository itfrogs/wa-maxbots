<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\Message;
use BushlanovDev\MaxMessengerBot\Models\MessageBody;
use BushlanovDev\MaxMessengerBot\Models\Recipient;
use BushlanovDev\MaxMessengerBot\Models\UserWithPhoto;
use BushlanovDev\MaxMessengerBot\Models\Updates\MessageEditedUpdate;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MessageEditedUpdate::class)]
#[UsesClass(Message::class)]
#[UsesClass(MessageBody::class)]
#[UsesClass(Recipient::class)]
#[UsesClass(UserWithPhoto::class)]
final class MessageEditedUpdateTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArray(): void
    {
        $data = [
            'update_type' => 'message_edited',
            'timestamp' => 1678887000,
            'message' => [
                'timestamp' => 1678886300,
                'body' => [
                    'mid' => 'mid.123.xyz',
                    'seq' => 1,
                    'text' => 'This is the new, edited text!',
                ],
                'recipient' => ['chat_type' => 'dialog', 'user_id' => 101],
                'sender' => [
                    'user_id' => 101,
                    'first_name' => 'Jane',
                    'is_bot' => false,
                    'last_activity_time' => 1678886000,
                ],
            ],
        ];

        $update = MessageEditedUpdate::fromArray($data);

        $this->assertInstanceOf(MessageEditedUpdate::class, $update);
        $this->assertSame(UpdateType::MessageEdited, $update->updateType);
        $this->assertSame(1678887000, $update->timestamp);
        $this->assertInstanceOf(Message::class, $update->message);
        $this->assertSame('This is the new, edited text!', $update->message->body->text);
        $this->assertSame('mid.123.xyz', $update->message->body->mid);
    }
}
