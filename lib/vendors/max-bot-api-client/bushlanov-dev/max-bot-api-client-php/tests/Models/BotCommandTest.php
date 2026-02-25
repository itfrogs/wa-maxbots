<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Models\AbstractModel;
use BushlanovDev\MaxMessengerBot\Models\BotCommand;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BotCommand::class)]
#[UsesClass(AbstractModel::class)]
final class BotCommandTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArrayWithAllData(): void
    {
        $data = [
            'name' => 'start',
            'description' => 'Start the bot',
        ];

        $command = BotCommand::fromArray($data);

        $this->assertInstanceOf(BotCommand::class, $command);
        $this->assertSame('start', $command->name);
        $this->assertSame('Start the bot', $command->description);

        $arrayResult = $command->toArray();

        $this->assertIsArray($arrayResult);
        $this->assertSame($data, $arrayResult);
    }

    #[Test]
    public function canBeCreatedFromArrayWithOptionalDataNull(): void
    {
        $command = BotCommand::fromArray([
            'name' => 'help',
            'description' => null,
        ]);

        $this->assertInstanceOf(BotCommand::class, $command);
        $this->assertSame('help', $command->name);
        $this->assertNull($command->description);
    }
}
