<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;
use BushlanovDev\MaxMessengerBot\Enums\ChatAdminPermission;

/**
 * Represents an administrator to be set in a chat, linking a user ID with their permissions.
 */
final readonly class ChatAdmin extends AbstractModel
{
    /**
     * @param int $userId The identifier of the user to be made an admin.
     * @param ChatAdminPermission[] $permissions The list of permissions to grant to the user.
     * @param string|null $alias Alias of the user.
     */
    public function __construct(
        public int $userId,
        #[ArrayOf(ChatAdminPermission::class)]
        public array $permissions,
        public ?string $alias = null,
    ) {
    }
}
