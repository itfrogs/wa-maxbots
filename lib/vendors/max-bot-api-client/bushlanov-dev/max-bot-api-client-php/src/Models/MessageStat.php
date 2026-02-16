<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

/**
 * Message statistics.
 */
final readonly class MessageStat extends AbstractModel
{
    /**
     * @param int $views Number of views.
     */
    public function __construct(public int $views)
    {
    }
}
