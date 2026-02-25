<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Models\Recipient;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Recipient::class)]
final class RecipientTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArray(): void
    {
        $data = [
            'chat_type' => 'chat',
            'chat_id' => 123,
        ];

        $receipt = Recipient::fromArray($data);

        $this->assertInstanceOf(Recipient::class, $receipt);
        $this->assertSame($data['chat_type'], $receipt->chatType->value);
        $this->assertSame($data['chat_id'], $receipt->chatId);

        $array = $receipt->toArray();

        $this->assertIsArray($array);
        unset($array['user_id']);
        $this->assertSame($data, $array);
    }
}
