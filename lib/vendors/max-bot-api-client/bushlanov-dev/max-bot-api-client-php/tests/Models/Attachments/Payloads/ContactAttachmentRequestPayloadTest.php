<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\ContactAttachmentRequestPayload;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ContactAttachmentRequestPayload::class)]
final class ContactAttachmentRequestPayloadTest extends TestCase
{
    #[Test]
    public function itConstructsWithAllPropertiesAndSerializesCorrectly(): void
    {
        $name = 'John Doe';
        $contactId = 123456;
        $vcfInfo = 'BEGIN:VCARD...END:VCARD';
        $vcfPhone = 'TEL:+79991234567';

        $payload = new ContactAttachmentRequestPayload($name, $contactId, $vcfInfo, $vcfPhone);

        $this->assertInstanceOf(ContactAttachmentRequestPayload::class, $payload);
        $this->assertSame($name, $payload->name);
        $this->assertSame($contactId, $payload->contactId);
        $this->assertSame($vcfInfo, $payload->vcfInfo);
        $this->assertSame($vcfPhone, $payload->vcfPhone);

        $expectedArray = [
            'name' => $name,
            'contact_id' => $contactId,
            'vcf_info' => $vcfInfo,
            'vcf_phone' => $vcfPhone,
        ];
        $this->assertEquals($expectedArray, $payload->toArray());
    }

    #[Test]
    public function itConstructsWithNullablePropertiesAndSerializesCorrectly(): void
    {
        $name = 'Jane Doe';
        $vcfPhone = 'TEL:+79876543210';

        $payload = new ContactAttachmentRequestPayload(
            name: $name,
            vcfPhone: $vcfPhone,
        );

        $this->assertSame($name, $payload->name);
        $this->assertNull($payload->contactId);
        $this->assertNull($payload->vcfInfo);
        $this->assertSame($vcfPhone, $payload->vcfPhone);

        $expectedArray = [
            'name' => $name,
            'contact_id' => null,
            'vcf_info' => null,
            'vcf_phone' => $vcfPhone,
        ];
        $this->assertEquals($expectedArray, $payload->toArray());
    }

    #[Test]
    public function itConstructsWithAllNullsAndSerializesCorrectly(): void
    {
        $payload = new ContactAttachmentRequestPayload();

        $this->assertNull($payload->name);
        $this->assertNull($payload->contactId);
        $this->assertNull($payload->vcfInfo);
        $this->assertNull($payload->vcfPhone);

        $expectedArray = [
            'name' => null,
            'contact_id' => null,
            'vcf_info' => null,
            'vcf_phone' => null,
        ];
        $this->assertEquals($expectedArray, $payload->toArray());
    }
}
