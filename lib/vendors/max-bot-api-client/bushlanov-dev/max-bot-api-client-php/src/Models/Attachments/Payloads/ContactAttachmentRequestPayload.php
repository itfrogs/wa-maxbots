<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

/**
 * Payload for a contact attachment request.
 */
final readonly class ContactAttachmentRequestPayload extends AbstractAttachmentRequestPayload
{
    /**
     * @param string|null $name Contact name.
     * @param int|null $contactId Contact identifier if it is a registered Max user.
     * @param string|null $vcfInfo Full information about the contact in VCF format.
     * @param string|null $vcfPhone Contact phone in VCF format.
     */
    public function __construct(
        public ?string $name = null,
        public ?int $contactId = null,
        public ?string $vcfInfo = null,
        public ?string $vcfPhone = null,
    ) {
    }
}
