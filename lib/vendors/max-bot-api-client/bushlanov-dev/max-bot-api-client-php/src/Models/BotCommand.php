<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

/**
 * Command supported by the bot.
 */
final readonly class BotCommand extends AbstractModel
{
    /**
     * @param string $name Command name (1 to 64 characters)
     * @param string|null $description Command description (1 to 128 characters)
     */
    public function __construct(
        public string $name,
        public ?string $description,
    ) {
    }
}
