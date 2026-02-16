<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline;

use BushlanovDev\MaxMessengerBot\Enums\InlineButtonType;

/**
 * Button send text in chat from user.
 */
final readonly class MessageButton extends AbstractInlineButton
{
    /**
     * @param string $text Text sending in chat from user.
     */
    public function __construct(string $text)
    {
        parent::__construct(InlineButtonType::Message, $text);
    }
}
