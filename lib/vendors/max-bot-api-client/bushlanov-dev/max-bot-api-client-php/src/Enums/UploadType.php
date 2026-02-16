<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Enums;

enum UploadType: string
{
    case Image = 'image';
    case Video = 'video';
    case Audio = 'audio';
    case File = 'file';
}
