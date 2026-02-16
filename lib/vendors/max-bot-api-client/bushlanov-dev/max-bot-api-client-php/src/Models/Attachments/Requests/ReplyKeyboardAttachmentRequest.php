<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\AbstractReplyButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\ReplyKeyboardAttachmentRequestPayload;

final readonly class ReplyKeyboardAttachmentRequest extends AbstractAttachmentRequest
{
    /**
     * @param AbstractReplyButton[][] $buttons
     * @param bool $direct
     * @param int|null $directUserId
     */
    public function __construct(
        array $buttons,
        bool $direct = false,
        ?int $directUserId = null
    ) {
        parent::__construct(
            AttachmentType::ReplyKeyboard,
            new ReplyKeyboardAttachmentRequestPayload($buttons, $direct, $directUserId),
        );
    }
}
