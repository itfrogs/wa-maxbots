<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Buttons\Inline;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\ChatButton;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ChatButton::class)]
final class ChatButtonTest extends TestCase
{
    #[Test]
    public function toArrayWithRequiredParameters(): void
    {
        $button = new ChatButton(
            text: 'Discuss Topic',
            chatTitle: 'Topic Discussion',
        );

        $expected = [
            'type' => 'chat',
            'text' => 'Discuss Topic',
            'chat_title' => 'Topic Discussion',
            'chat_description' => null,
            'start_payload' => null,
            'uuid' => null,
        ];

        $this->assertEquals($expected, $button->toArray());
    }

    #[Test]
    public function toArrayWithAllParameters(): void
    {
        $button = new ChatButton(
            text: 'Join Project Chat',
            chatTitle: 'Project Alpha Chat',
            chatDescription: 'Chat for team members of Project Alpha.',
            startPayload: 'project-alpha-join',
            uuid: 123456789
        );

        $expected = [
            'type' => 'chat',
            'text' => 'Join Project Chat',
            'chat_title' => 'Project Alpha Chat',
            'chat_description' => 'Chat for team members of Project Alpha.',
            'start_payload' => 'project-alpha-join',
            'uuid' => 123456789,
        ];

        $this->assertEquals($expected, $button->toArray());
    }
}
