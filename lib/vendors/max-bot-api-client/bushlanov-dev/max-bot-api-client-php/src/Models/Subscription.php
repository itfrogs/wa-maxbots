<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;
use BushlanovDev\MaxMessengerBot\Enums\UpdateType;

/**
 * Information about webhook subscriptions.
 */
final readonly class Subscription extends AbstractModel
{
    /**
     * @param string $url URL webhook.
     * @param int $time Unix-time of creating a subscription.
     * @param UpdateType[]|null $updateTypes List of update types.
     * @param string|null $version Version of the API.
     */
    public function __construct(
        public string $url,
        public int $time,
        #[ArrayOf(UpdateType::class)]
        public ?array $updateTypes,
        public ?string $version,
    ) {
    }
}
