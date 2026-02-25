<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\ModelFactory;
use BushlanovDev\MaxMessengerBot\Models\Attachments\AbstractAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\ContactAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\ContactAttachmentPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoAttachmentPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\PhotoAttachment;
use BushlanovDev\MaxMessengerBot\Models\Markup\AbstractMarkup;
use BushlanovDev\MaxMessengerBot\Models\Markup\StrongMarkup;
use BushlanovDev\MaxMessengerBot\Models\Message;
use BushlanovDev\MaxMessengerBot\Models\MessageBody;
use BushlanovDev\MaxMessengerBot\Models\Recipient;
use BushlanovDev\MaxMessengerBot\Models\UserWithPhoto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MessageBody::class)]
#[UsesClass(AbstractAttachment::class)]
#[UsesClass(ContactAttachment::class)]
#[UsesClass(ContactAttachmentPayload::class)]
#[UsesClass(PhotoAttachmentPayload::class)]
#[UsesClass(PhotoAttachment::class)]
#[UsesClass(ModelFactory::class)]
#[UsesClass(AbstractMarkup::class)]
#[UsesClass(StrongMarkup::class)]
#[UsesClass(Message::class)]
#[UsesClass(Recipient::class)]
#[UsesClass(UserWithPhoto::class)]
final class MessageBodyTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArrayWithAllData(): void
    {
        $data = [
            'mid' => 'mid.456.xyz',
            'seq' => 101,
            'text' => 'Hello, **world**!',
            'attachments' => null,
            'markup' => null,
        ];

        $messageBody = MessageBody::fromArray($data);

        $this->assertInstanceOf(MessageBody::class, $messageBody);
        $this->assertSame($data['mid'], $messageBody->mid);
        $this->assertSame($data['seq'], $messageBody->seq);
        $this->assertSame($data['text'], $messageBody->text);

        $array = $messageBody->toArray();

        $this->assertIsArray($array);
        $this->assertSame($data, $array);
    }

    #[Test]
    public function canBeCreatedFromArrayWithOptionalDataNull(): void
    {
        $data = [
            'mid' => 'mid.456.xyz',
            'seq' => 101,
            'text' => null,
            'attachments' => null,
            'markup' => null,
        ];

        $messageBody = MessageBody::fromArray($data);

        $this->assertInstanceOf(MessageBody::class, $messageBody);
        $this->assertSame($data['mid'], $messageBody->mid);
        $this->assertSame($data['seq'], $messageBody->seq);
        $this->assertNull($messageBody->text);

        $array = $messageBody->toArray();

        $this->assertIsArray($array);
        $this->assertSame($data, $array);
    }

    #[Test]
    public function createMessageCorrectlyHydratesComplexMessageBody(): void
    {
        $messageData = [
            'timestamp' => time(),
            'body' => [
                'mid' => 'mid.poly.test',
                'seq' => 200,
                'text' => 'Message with mixed content',
                'attachments' => [
                    [
                        'type' => 'contact',
                        'payload' => [
                            'vcf_info' => 'vcf...',
                            'max_info' => [
                                'user_id' => 1111,
                                'first_name' => 'aaaaa',
                                'is_bot' => false,
                                'last_activity_time' => 1754385571000,
                            ],
                        ],
                    ],
                    [
                        'type' => 'image',
                        'payload' => ['photo_id' => 1, 'token' => 't', 'url' => 'u'],
                    ]
                ],
                'markup' => [
                    ['type' => 'strong', 'from' => 0, 'length' => 7],
                ]
            ],
            'recipient' => ['chat_type' => 'dialog', 'user_id' => 123],
        ];

        $factory = new ModelFactory();
        $message = $factory->createMessage($messageData);

        $this->assertInstanceOf(Message::class, $message);
        $this->assertInstanceOf(MessageBody::class, $message->body);

        $attachments = $message->body->attachments;
        $this->assertIsArray($attachments);
        $this->assertCount(2, $attachments);
        $this->assertInstanceOf(ContactAttachment::class, $attachments[0]);
        $this->assertInstanceOf(PhotoAttachment::class, $attachments[1]);

        $markup = $message->body->markup;
        $this->assertIsArray($markup);
        $this->assertCount(1, $markup);
        $this->assertInstanceOf(StrongMarkup::class, $markup[0]);
        $this->assertSame(0, $markup[0]->from);
    }
}
