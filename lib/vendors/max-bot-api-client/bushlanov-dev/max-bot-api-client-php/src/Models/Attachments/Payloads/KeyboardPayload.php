<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\AbstractModel;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\AbstractInlineButton;

/**
 * Represents an inline keyboard structure.
 */
final readonly class KeyboardPayload extends AbstractModel
{
    /**
     * @param AbstractInlineButton[][] $buttons Two-dimensional array of buttons.
     */
    public function __construct(public array $buttons)
    {
    }
}
