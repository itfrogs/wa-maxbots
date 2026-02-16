<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

/**
 * An abstract base for PATCH request models. It uses a variadic constructor
 * to capture named arguments, tracking which fields were explicitly set.
 */
abstract readonly class AbstractPatchModel extends AbstractModel
{
    /**
     * @var array<int|string, mixed>
     */
    protected array $patchData;

    /**
     * Captures all passed named arguments into an associative array.
     *
     * @param mixed ...$params
     */
    public function __construct(...$params)
    {
        $this->patchData = $params;
    }

    /**
     * @return array<string, mixed>
     * @throws \ReflectionException
     */
    final public function toArray(): array
    {
        $result = [];
        foreach ($this->patchData as $key => $value) {
            $jsonKey = $this->toSnakeCase((string)$key);
            $result[$jsonKey] = $this->convertValue($value);
        }

        return $result;
    }
}
