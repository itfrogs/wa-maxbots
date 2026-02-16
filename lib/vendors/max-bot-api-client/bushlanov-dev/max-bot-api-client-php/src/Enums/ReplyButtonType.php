<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Enums;

enum ReplyButtonType: string
{
    case Message = 'message';
    case UserGeoLocation = 'user_geo_location';
    case UserContact = 'user_contact';
}
