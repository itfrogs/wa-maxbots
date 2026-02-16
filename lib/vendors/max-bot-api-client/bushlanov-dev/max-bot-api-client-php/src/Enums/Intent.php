<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Enums;

enum Intent: string
{
    case Positive = 'positive';
    case Negative = 'negative';
    case Default = 'default';
}
