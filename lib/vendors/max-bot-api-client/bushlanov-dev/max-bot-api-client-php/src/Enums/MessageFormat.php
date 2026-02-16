<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Enums;

enum MessageFormat: string
{
    case Markdown = 'markdown';
    case Html = 'html';
}
