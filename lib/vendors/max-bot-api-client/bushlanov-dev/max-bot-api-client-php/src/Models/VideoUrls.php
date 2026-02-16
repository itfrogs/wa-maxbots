<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

/**
 * Contains URLs for a video attachment in various resolutions.
 */
final readonly class VideoUrls extends AbstractModel
{
    /**
     * @param string|null $mp4_1080 Video URL in 1080p resolution, if available.
     * @param string|null $mp4_720 Video URL in 720p resolution, if available.
     * @param string|null $mp4_480 Video URL in 480p resolution, if available.
     * @param string|null $mp4_360 Video URL in 360p resolution, if available.
     * @param string|null $mp4_240 Video URL in 240p resolution, if available.
     * @param string|null $mp4_144 Video URL in 144p resolution, if available.
     * @param string|null $hls Live streaming URL (HLS), if available.
     */
    public function __construct(
        public ?string $mp4_1080 = null,
        public ?string $mp4_720 = null,
        public ?string $mp4_480 = null,
        public ?string $mp4_360 = null,
        public ?string $mp4_240 = null,
        public ?string $mp4_144 = null,
        public ?string $hls = null,
    ) {
    }
}
