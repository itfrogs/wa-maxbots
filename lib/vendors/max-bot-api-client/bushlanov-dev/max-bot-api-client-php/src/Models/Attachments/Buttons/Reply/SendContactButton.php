<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply;

use BushlanovDev\MaxMessengerBot\Enums\ReplyButtonType;

final readonly class SendContactButton extends AbstractReplyButton
{
    /**
     * @param string $text Visible text of the button.
     */
    public function __construct(string $text)
    {
        parent::__construct(ReplyButtonType::UserContact, $text);
    }
}
