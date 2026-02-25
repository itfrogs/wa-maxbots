<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Enums\MessageLinkType;
use BushlanovDev\MaxMessengerBot\Models\MessageLink;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(MessageLink::class)]
final class MessageLinkTest extends TestCase
{
    #[Test]
    public function toArraySerializesCorrectly(): void
    {
        $messageLink = new MessageLink(MessageLinkType::Forward, '123');

        $expectedArray = [
            'type' => MessageLinkType::Forward->value,
            'mid' => '123',
        ];

        $resultArray = $messageLink->toArray();

        $this->assertSame($expectedArray, $resultArray);
        $this->assertSame(MessageLinkType::Forward, $messageLink->type);
        $this->assertSame('123', $messageLink->mid);
    }
}
