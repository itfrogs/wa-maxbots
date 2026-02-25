<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoAttachmentRequestPayload;
use BushlanovDev\MaxMessengerBot\Models\VideoAttachmentDetails;
use BushlanovDev\MaxMessengerBot\Models\VideoUrls;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(VideoAttachmentDetails::class)]
#[UsesClass(VideoUrls::class)]
#[UsesClass(PhotoAttachmentRequestPayload::class)]
final class VideoAttachmentDetailsTest extends TestCase
{
    #[Test]
    public function canBeCreatedWithAllData(): void
    {
        $data = [
            'token' => 'video_token_123',
            'width' => 1920,
            'height' => 1080,
            'duration' => 125,
            'urls' => ['mp4_1080' => 'http://example.com/video.mp4'],
            'thumbnail' => ['token' => 'thumb_token_456'],
        ];

        $details = VideoAttachmentDetails::fromArray($data);

        $this->assertInstanceOf(VideoAttachmentDetails::class, $details);
        $this->assertSame('video_token_123', $details->token);
        $this->assertSame(125, $details->duration);
        $this->assertInstanceOf(VideoUrls::class, $details->urls);
        $this->assertInstanceOf(PhotoAttachmentRequestPayload::class, $details->thumbnail);
        $this->assertSame('http://example.com/video.mp4', $details->urls->mp4_1080);
    }
}
