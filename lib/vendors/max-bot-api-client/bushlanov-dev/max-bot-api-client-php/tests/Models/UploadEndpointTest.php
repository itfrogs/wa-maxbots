<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Models\UploadEndpoint;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(UploadEndpoint::class)]
final class UploadEndpointTest extends TestCase
{
    #[Test]
    public function canBeCreatedFromArray(): void
    {
        $data = [
            'url' => 'https://example.com/upload',
            'token' => 'token',
        ];

        $uploadEndpoint = UploadEndpoint::fromArray($data);

        $this->assertInstanceOf(UploadEndpoint::class, $uploadEndpoint);
        $this->assertSame($data['url'], $uploadEndpoint->url);
        $this->assertSame($data['token'], $uploadEndpoint->token);
    }

    #[Test]
    public function canBeCreatedFromArrayWithoutToken(): void
    {
        $data = [
            'url' => 'https://example.com/upload',
        ];

        $uploadEndpoint = UploadEndpoint::fromArray($data);

        $this->assertInstanceOf(UploadEndpoint::class, $uploadEndpoint);
        $this->assertSame($data['url'], $uploadEndpoint->url);
        $this->assertNull($uploadEndpoint->token);
    }
}
