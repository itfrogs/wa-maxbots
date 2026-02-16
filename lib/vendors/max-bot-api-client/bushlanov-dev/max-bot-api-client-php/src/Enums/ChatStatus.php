<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Enums;

enum ChatStatus: string
{
    case Active = 'active';
    case Removed = 'removed';
    case Left = 'left';
    case Closed = 'closed';
    case Suspended = 'suspended';
}
