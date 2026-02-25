<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\MediaAttachmentPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\VideoThumbnail;
use BushlanovDev\MaxMessengerBot\Models\Attachments\VideoAttachment;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(VideoAttachment::class)]
#[UsesClass(MediaAttachmentPayload::class)]
#[UsesClass(VideoThumbnail::class)]
final class VideoAttachmentTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArray(): void
    {
        $data = [
            'type' => 'video',
            'payload' => ['url' => 'u', 'token' => 't'],
            'thumbnail' => ['url' => 'thumb_url'],
            'width' => 1920,
            'height' => 1080,
            'duration' => 120,
        ];
        $attachment = VideoAttachment::fromArray($data);
        $this->assertInstanceOf(VideoAttachment::class, $attachment);
        $this->assertInstanceOf(MediaAttachmentPayload::class, $attachment->payload);
        $this->assertInstanceOf(VideoThumbnail::class, $attachment->thumbnail);
        $this->assertSame(120, $attachment->duration);
    }
}
