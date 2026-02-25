<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\AbstractAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\ShareAttachmentRequestPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\ShareAttachment;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ShareAttachment::class)]
#[UsesClass(AbstractAttachment::class)]
#[UsesClass(ShareAttachmentRequestPayload::class)]
final class ShareAttachmentTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArray(): void
    {
        $data = [
            'type' => 'share',
            'payload' => ['url' => 'https://dev.max.ru'],
            'title' => 'Max Bot API',
            'description' => 'Documentation for Max Bot API',
            'image_url' => 'https://dev.max.ru/image.png',
        ];

        $attachment = ShareAttachment::fromArray($data);

        $this->assertInstanceOf(ShareAttachment::class, $attachment);
        $this->assertSame(AttachmentType::Share, $attachment->type);
        $this->assertSame('Max Bot API', $attachment->title);
        $this->assertInstanceOf(ShareAttachmentRequestPayload::class, $attachment->payload);
        $this->assertSame('https://dev.max.ru', $attachment->payload->url);
    }
}
