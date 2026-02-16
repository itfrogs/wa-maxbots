<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

/**
 * Simple response to request.
 */
final readonly class Result extends AbstractModel
{
    /**
     * @param bool $success true if request was successful, false otherwise.
     * @param string|null $message Explanatory message if the result was not successful.
     */
    public function __construct(
        public bool $success,
        public ?string $message,
    ) {
    }
}
