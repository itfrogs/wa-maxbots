<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\Callback;
use BushlanovDev\MaxMessengerBot\Models\Message;
use BushlanovDev\MaxMessengerBot\Models\MessageBody;
use BushlanovDev\MaxMessengerBot\Models\Recipient;
use BushlanovDev\MaxMessengerBot\Models\Updates\MessageCallbackUpdate;
use BushlanovDev\MaxMessengerBot\Models\UserWithPhoto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MessageCallbackUpdate::class)]
#[UsesClass(Callback::class)]
#[UsesClass(Message::class)]
#[UsesClass(MessageBody::class)]
#[UsesClass(Recipient::class)]
#[UsesClass(UserWithPhoto::class)]
final class MessageCallbackUpdateTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArrayWithAllData(): void
    {
        $data = [
            'update_type' => 'message_callback',
            'timestamp' => 1678886400,
            'callback' => [
                'timestamp' => 1678886400,
                'callback_id' => 'cb.12345.abc',
                'payload' => 'button_1_pressed',
                'user' => [
                    'user_id' => 101, 'first_name' => 'Jane',
                    'is_bot' => false, 'last_activity_time' => 1678886000,
                ],
            ],
            'message' => [
                'timestamp' => 1678886300,
                'body' => ['mid' => 'mid.123', 'seq' => 1, 'text' => 'Press a button'],
                'recipient' => ['chat_type' => 'dialog', 'user_id' => 101],
            ],
            'user_locale' => 'en-US',
        ];

        $update = MessageCallbackUpdate::fromArray($data);

        $this->assertInstanceOf(MessageCallbackUpdate::class, $update);
        $this->assertSame(UpdateType::MessageCallback, $update->updateType);
        $this->assertInstanceOf(Callback::class, $update->callback);
        $this->assertInstanceOf(Message::class, $update->message);
        $this->assertSame('en-US', $update->userLocale);
        $this->assertSame('button_1_pressed', $update->callback->payload);
        $this->assertSame('mid.123', $update->message->body->mid);
    }

    #[Test]
    public function canBeCreatedWithNullableFieldsAsNull(): void
    {
        $data = [
            'update_type' => 'message_callback',
            'timestamp' => 1678886400,
            'callback' => [
                'timestamp' => 1678886400,
                'callback_id' => 'cb.12345.abc',
                'payload' => 'button_1_pressed',
                'user' => [
                    'user_id' => 101, 'first_name' => 'Jane',
                    'is_bot' => false, 'last_activity_time' => 1678886000,
                ],
            ],
            'message' => null,
            'user_locale' => null,
        ];

        $update = MessageCallbackUpdate::fromArray($data);

        $this->assertInstanceOf(MessageCallbackUpdate::class, $update);
        $this->assertNull($update->message);
        $this->assertNull($update->userLocale);
    }
}
