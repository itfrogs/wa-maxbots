<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Buttons\Inline;

use BushlanovDev\MaxMessengerBot\Enums\InlineButtonType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\OpenAppButton;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(OpenAppButton::class)]
final class OpenAppButtonTest extends TestCase
{
    #[Test]
    public function toArraySerializesCorrectly(): void
    {
        $button = new OpenAppButton('Test Button', 'MyWebApp', 123);

        $expectedArray = [
            'web_app' => 'MyWebApp',
            'contact_id' => 123,
            'type' => InlineButtonType::OpenApp->value,
            'text' => 'Test Button',
        ];

        $resultArray = $button->toArray();

        $this->assertSame($expectedArray, $resultArray);
    }

    #[Test]
    public function fromArrayHydratesCorrectly(): void
    {
        $data = [
            'type' => 'open_app',
            'text' => 'Launch',
            'web_app' => 'SomeApp',
            'contact_id' => 456,
        ];

        $button = OpenAppButton::fromArray($data);

        $this->assertInstanceOf(OpenAppButton::class, $button);
        $this->assertSame(InlineButtonType::OpenApp, $button->type);
        $this->assertSame('Launch', $button->text);
        $this->assertSame('SomeApp', $button->webApp);
        $this->assertSame(456, $button->contactId);
    }
}
