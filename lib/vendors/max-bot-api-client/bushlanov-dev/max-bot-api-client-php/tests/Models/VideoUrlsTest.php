<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Models\VideoUrls;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(VideoUrls::class)]
final class VideoUrlsTest extends TestCase
{
    #[Test]
    public function canBeCreatedWithAllFields(): void
    {
        $data = [
            'mp4_720' => 'http://example.com/video_720p.mp4',
            'mp4_480' => 'http://example.com/video_480p.mp4',
        ];

        $urls = VideoUrls::fromArray($data);

        $this->assertInstanceOf(VideoUrls::class, $urls);
        $this->assertSame('http://example.com/video_720p.mp4', $urls->mp4_720);
        $this->assertNull($urls->mp4_1080);
    }
}
