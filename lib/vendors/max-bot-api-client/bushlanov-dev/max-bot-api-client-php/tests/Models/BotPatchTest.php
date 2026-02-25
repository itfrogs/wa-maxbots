<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoAttachmentRequestPayload;
use BushlanovDev\MaxMessengerBot\Models\BotPatch;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BotPatch::class)]
#[UsesClass(PhotoAttachmentRequestPayload::class)]
final class BotPatchTest extends TestCase
{
    #[Test]
    public function toArrayIncludesOnlyExplicitlySetFields(): void
    {
        $patch = new BotPatch(name: 'New Name');
        $this->assertEquals(['name' => 'New Name'], $patch->toArray());
    }

    #[Test]
    public function toArrayIncludesFieldsSetToNull(): void
    {
        $patch = new BotPatch(description: null);
        $this->assertEquals(['description' => null], $patch->toArray());
    }

    #[Test]
    public function toArrayHandlesMultipleSetFields(): void
    {
        $photoPayload = new PhotoAttachmentRequestPayload(token: 'photo123');
        $patch = new BotPatch(
            name: 'Updated Bot',
            description: null,
            photo: $photoPayload
        );

        $expected = [
            'name' => 'Updated Bot',
            'description' => null,
            'photo' => [
                'url' => null,
                'token' => 'photo123',
                'photos' => null,
            ],
        ];

        $this->assertEquals($expected, $patch->toArray());
    }

    #[Test]
    public function toArrayIsEmptyWhenNoArgumentsPassed(): void
    {
        $patch = new BotPatch();
        $this->assertEmpty($patch->toArray());
    }

    #[Test]
    public function toArrayFirstAndLastName(): void
    {
        $patch = new BotPatch(first_name: 'New Name', last_name: null);
        $this->assertEquals(['first_name' => 'New Name', 'last_name' => null], $patch->toArray());
    }
}
