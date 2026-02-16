<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class ArrayOf
{
    /**
     * @param class-string $class Class name (model or enum)
     */
    public function __construct(public string $class)
    {
    }
}
