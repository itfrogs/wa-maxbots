<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Enums;

enum ChatType: string
{
    case Dialog = 'dialog';
    case Chat = 'chat';
    case Channel = 'channel';
}
