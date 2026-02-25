<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\AbstractModel;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\VideoThumbnail;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(VideoThumbnail::class)]
#[UsesClass(AbstractModel::class)]
final class VideoThumbnailTest extends TestCase
{
    #[Test]
    public function canBeCreatedAndSerialized(): void
    {
        $data = [
            'url' => 'https://example.com/video_thumbnail.jpg',
        ];

        $thumbnail = VideoThumbnail::fromArray($data);

        $this->assertInstanceOf(VideoThumbnail::class, $thumbnail);
        $this->assertSame('https://example.com/video_thumbnail.jpg', $thumbnail->url);

        $this->assertEquals($data, $thumbnail->toArray());

        $directInstance = new VideoThumbnail('https://example.com/video_thumbnail.jpg');
        $this->assertSame('https://example.com/video_thumbnail.jpg', $directInstance->url);
    }
}
