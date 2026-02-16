<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Markup;

use BushlanovDev\MaxMessengerBot\Enums\MarkupType;

/**
 * Represents a user mention in the text.
 */
final readonly class UserMentionMarkup extends AbstractMarkup
{
    /**
     * @param int $from Element start index (zero-based) in text.
     * @param int $length Length of the markup element.
     * @param string|null $userLink "@username" of the mentioned user.
     * @param int|null $userId Identifier of the mentioned user without a username.
     */
    public function __construct(
        int $from,
        int $length,
        public ?string $userLink,
        public ?int $userId,
    ) {
        parent::__construct(MarkupType::UserMention, $from, $length);
    }
}
