<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Models\Attachments\AudioAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\MediaAttachmentPayload;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AudioAttachment::class)]
#[UsesClass(MediaAttachmentPayload::class)]
final class AudioAttachmentTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArray(): void
    {
        $data = ['type' => 'audio', 'payload' => ['url' => 'u', 'token' => 't'], 'transcription' => 'Hello world.'];
        $attachment = AudioAttachment::fromArray($data);
        $this->assertInstanceOf(AudioAttachment::class, $attachment);
        $this->assertSame('Hello world.', $attachment->transcription);
    }
}
