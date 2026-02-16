<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\AbstractReplyButton;

final readonly class ReplyKeyboardAttachment extends AbstractAttachment
{
    /**
     * @param AbstractReplyButton[][] $buttons
     */
    public function __construct(public array $buttons)
    {
        parent::__construct(AttachmentType::ReplyKeyboard);
    }
}
