<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Markup;

use BushlanovDev\MaxMessengerBot\Enums\MarkupType;

/**
 * Represents a link in the text.
 */
final readonly class LinkMarkup extends AbstractMarkup
{
    /**
     * @param int $from Element start index (zero-based) in text.
     * @param int $length Length of the markup element.
     * @param string $url Link's URL.
     */
    public function __construct(
        int $from,
        int $length,
        public string $url,
    ) {
        parent::__construct(MarkupType::Link, $from, $length);
    }
}
