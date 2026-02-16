<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Enums;

enum MessageLinkType: string
{
    case Forward = 'forward';
    case Reply = 'reply';
}
