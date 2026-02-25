<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Models\Attachments\FileAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\FileAttachmentPayload;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FileAttachment::class)]
#[UsesClass(FileAttachmentPayload::class)]
final class FileAttachmentTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArray(): void
    {
        $data = [
            'type' => 'file',
            'payload' => ['url' => 'u', 'token' => 't'],
            'filename' => 'doc.pdf',
            'size' => 1024,
        ];
        $attachment = FileAttachment::fromArray($data);
        $this->assertInstanceOf(FileAttachment::class, $attachment);
        $this->assertSame('doc.pdf', $attachment->filename);
        $this->assertSame(1024, $attachment->size);
    }
}
