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
    public ?string $payload;

    /**
     * @param string $text Visible button text (1 to 128 characters).
     * @param string|null $webApp The public name (username) of the bot or a link to it, whose mini-application should be launched.
     * @param int|null $contactId The ID of the bot whose mini-app should be launched.
     * @param string|null $payload Init param which would be passed in mini-application initData (1 to 512 characters, allowed A-Z, a-z, 0-9, _(underscore) , - (minus) ).
     */
    public function __construct(string $text, ?string $webApp = null, ?int $contactId = null, ?string $payload = null)
    {
        parent::__construct(InlineButtonType::OpenApp, $text);

        $this->webApp = $webApp;
        $this->contactId = $contactId;
        $this->payload = $payload;
    }
}
