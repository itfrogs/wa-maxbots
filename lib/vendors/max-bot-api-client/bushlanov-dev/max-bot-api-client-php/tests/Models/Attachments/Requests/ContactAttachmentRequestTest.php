<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\ContactAttachmentRequestPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Requests\ContactAttachmentRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ContactAttachmentRequest::class)]
#[UsesClass(ContactAttachmentRequestPayload::class)]
final class ContactAttachmentRequestTest extends TestCase
{
    #[Test]
    public function itCreatesRequestWithAllFieldsAndSerializes(): void
    {
        $request = new ContactAttachmentRequest(
            name: 'John Doe',
            contactId: 12345,
            vcfInfo: 'BEGIN:VCARD...',
            vcfPhone: 'TEL:+1234567890',
        );

        $this->assertInstanceOf(ContactAttachmentRequest::class, $request);
        $this->assertSame(AttachmentType::Contact, $request->type);
        $this->assertInstanceOf(ContactAttachmentRequestPayload::class, $request->payload);
        $this->assertSame('John Doe', $request->payload->name);
        $this->assertSame(12345, $request->payload->contactId);

        $expectedArray = [
            'type' => 'contact',
            'payload' => [
                'name' => 'John Doe',
                'contact_id' => 12345,
                'vcf_info' => 'BEGIN:VCARD...',
                'vcf_phone' => 'TEL:+1234567890',
            ],
        ];
        $this->assertEquals($expectedArray, $request->toArray());
    }

    #[Test]
    public function itCreatesRequestWithOnlySomeFields(): void
    {
        $request = new ContactAttachmentRequest(name: 'Jane Doe', vcfPhone: 'TEL:+9876543210');

        $expectedArray = [
            'type' => 'contact',
            'payload' => [
                'name' => 'Jane Doe',
                'contact_id' => null,
                'vcf_info' => null,
                'vcf_phone' => 'TEL:+9876543210',
            ],
        ];
        $this->assertEquals($expectedArray, $request->toArray());
    }
}
