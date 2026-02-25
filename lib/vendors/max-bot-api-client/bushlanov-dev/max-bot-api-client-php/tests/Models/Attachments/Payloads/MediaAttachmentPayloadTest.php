<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\MediaAttachmentPayload;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(MediaAttachmentPayload::class)]
final class MediaAttachmentPayloadTest extends TestCase
{
    #[Test]
    public function canBeCreatedAndSerialized(): void
    {
        $payload = new MediaAttachmentPayload('https://example.com/video.mp4', 'video_token_123');
        $this->assertSame('https://example.com/video.mp4', $payload->url);
        $this->assertSame('video_token_123', $payload->token);
        $this->assertEquals(
            ['url' => 'https://example.com/video.mp4', 'token' => 'video_token_123'],
            $payload->toArray(),
        );
    }
}
