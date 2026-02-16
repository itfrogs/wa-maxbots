<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline;

use BushlanovDev\MaxMessengerBot\Enums\InlineButtonType;
use BushlanovDev\MaxMessengerBot\Models\AbstractModel;

abstract readonly class AbstractInlineButton extends AbstractModel
{
    /**
     * @param InlineButtonType $type The type of the inline button.
     * @param string $text Visible text of the button.
     */
    public function __construct(
        public InlineButtonType $type,
        public string $text,
    ) {
    }
}
