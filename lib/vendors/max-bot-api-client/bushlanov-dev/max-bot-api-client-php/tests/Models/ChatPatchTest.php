<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoAttachmentRequestPayload;
use BushlanovDev\MaxMessengerBot\Models\ChatPatch;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ChatPatch::class)]
#[UsesClass(PhotoAttachmentRequestPayload::class)]
final class ChatPatchTest extends TestCase
{
    #[Test]
    public function toArrayIncludesOnlySetFields(): void
    {
        $patch = new ChatPatch(title: 'New Title');
        $this->assertEquals(['title' => 'New Title'], $patch->toArray());
    }

    #[Test]
    public function toArrayHandlesMultipleFields(): void
    {
        $photoPayload = new PhotoAttachmentRequestPayload(token: 'icon_token');
        $patch = new ChatPatch(
            title: 'Updated Chat',
            pin: 'mid.12345',
            icon: $photoPayload
        );

        $expected = [
            'title' => 'Updated Chat',
            'pin' => 'mid.12345',
            'icon' => [
                'url' => null,
                'token' => 'icon_token',
                'photos' => null,
            ],
        ];

        $this->assertEquals($expected, $patch->toArray());
    }

    #[Test]
    public function toArrayIsEmptyForEmptyPatch(): void
    {
        $patch = new ChatPatch();
        $this->assertEmpty($patch->toArray());
    }
}
