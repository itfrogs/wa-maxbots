<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline;

use BushlanovDev\MaxMessengerBot\Enums\InlineButtonType;

/**
 * Makes a user to follow a link.
 */
final readonly class LinkButton extends AbstractInlineButton
{
    public string $url;

    /**
     * @param string $text Visible button text (1 to 128 characters).
     * @param string $url Button URL (1 to 2048 characters).
     */
    public function __construct(string $text, string $url)
    {
        parent::__construct(InlineButtonType::Link, $text);

        $this->url = $url;
    }
}
