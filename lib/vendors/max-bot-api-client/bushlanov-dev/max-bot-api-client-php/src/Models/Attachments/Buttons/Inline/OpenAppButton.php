<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline;

use BushlanovDev\MaxMessengerBot\Enums\InlineButtonType;

/**
 * Opens the bot's mini-application.
 */
final readonly class OpenAppButton extends AbstractInlineButton
{
    public ?string $webApp;
    public ?int $contactId;

    /**
     * @param string $text Visible button text (1 to 128 characters).
     * @param string|null $webApp The public name (username) of the bot or a link to it, whose mini-application should be launched.
     * @param int|null $contactId The ID of the bot whose mini-app should be launched.
     */
    public function __construct(string $text, ?string $webApp = null, ?int $contactId = null)
    {
        parent::__construct(InlineButtonType::OpenApp, $text);

        $this->webApp = $webApp;
        $this->contactId = $contactId;
    }
}
