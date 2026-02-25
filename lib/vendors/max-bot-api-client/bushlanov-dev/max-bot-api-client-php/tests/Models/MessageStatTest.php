<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Models\MessageStat;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(MessageStat::class)]
final class MessageStatTest extends TestCase
{
    #[Test]
    public function canBeCreatedAndSerialized(): void
    {
        $data = ['views' => 1234];
        $stat = MessageStat::fromArray($data);

        $this->assertInstanceOf(MessageStat::class, $stat);
        $this->assertSame(1234, $stat->views);
        $this->assertEquals($data, $stat->toArray());
    }
}
