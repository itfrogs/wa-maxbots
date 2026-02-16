<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

use BackedEnum;
use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;

abstract readonly class AbstractModel
{
    /**
     * @param array<string, mixed> $data
     *
     * @return static
     * @throws ReflectionException
     */
    public static function fromArray(array $data): static
    {
        $reflectionClass = new ReflectionClass(static::class);
        $constructorArgs = [];

        foreach ($reflectionClass->getConstructor()?->getParameters() ?? [] as $param) {
            $phpPropertyName = $param->getName();
            $property = $reflectionClass->getProperty($phpPropertyName);

            $jsonKey = self::toSnakeCase($phpPropertyName);
            $rawValue = $data[$jsonKey] ?? null;

            if (!array_key_exists($jsonKey, $data) && $param->isDefaultValueAvailable()) {
                $constructorArgs[$phpPropertyName] = $param->getDefaultValue();
                continue;
            }

            $constructorArgs[$phpPropertyName] = self::castValue($rawValue, $property);
        }

        return new static(...$constructorArgs); // @phpstan-ignore-line
    }

    /**
     * @return array<string, mixed>
     * @throws ReflectionException
     */
    public function toArray(): array
    {
        $reflectionClass = new ReflectionClass($this);
        $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);
        $result = [];

        foreach ($properties as $property) {
            if (!$property->isInitialized($this)) {
                continue;
            }

            $phpPropertyName = $property->getName();
            $value = $property->getValue($this);

            $jsonKey = self::toSnakeCase($phpPropertyName);

            $result[$jsonKey] = $this->convertValue($value);
        }

        return $result;
    }

    /**
     * @param string $input
     *
     * @return string
     */
    protected static function toSnakeCase(string $input): string
    {
        return strtolower((string)preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     * @throws ReflectionException
     */
    protected function convertValue(mixed $value): mixed
    {
        if ($value instanceof AbstractModel) {
            return $value->toArray();
        }

        if (is_array($value)) {
            return array_map([$this, 'convertValue'], $value);
        }

        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @param ReflectionProperty $property
     *
     * @return mixed
     * @throws ReflectionException
     */
    private static function castValue(mixed $value, ReflectionProperty $property): mixed
    {
        $type = $property->getType();

        if (is_null($value) || !$type instanceof ReflectionNamedType) {
            return $value;
        }

        $typeName = $type->getName();

        if (is_object($value) && is_a($value, $typeName)) {
            return $value;
        }

        if ($type->isBuiltin()) {
            return match ($typeName) {
                'int' => (int)$value,
                'string' => (string)$value,
                'bool' => (bool)$value,
                'float' => (float)$value,
                'array' => self::castArray($value, $property),
                default => $value,
            };
        }

        if (is_subclass_of($typeName, BackedEnum::class)) {
            return $typeName::from($value);
        }

        if (is_subclass_of($typeName, self::class) && is_array($value)) {
            return $typeName::fromArray($value);
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @param ReflectionProperty $property
     *
     * @return array<string, mixed>
     * @throws ReflectionException
     */
    private static function castArray(mixed $value, ReflectionProperty $property): array
    {
        if (!is_array($value)) {
            return (array)$value;
        }

        $attributes = $property->getAttributes(ArrayOf::class);

        if (empty($attributes)) {
            return $value;
        }

        /** @var ArrayOf $arrayOfAttribute */
        $arrayOfAttribute = $attributes[0]->newInstance();
        $itemClassName = $arrayOfAttribute->class;

        if (is_subclass_of($itemClassName, BackedEnum::class)) {
            return array_map(fn($item) => $itemClassName::from($item), $value);
        }

        if (is_subclass_of($itemClassName, self::class)) {
            return array_map(
                fn($item) => is_a($item, $itemClassName) ? $item : $itemClassName::fromArray($item),
                $value,
            );
        }

        return $value;
    }
}
