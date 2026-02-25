<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;
use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\AbstractModel;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use stdClass;

#[CoversClass(AbstractModel::class)]
#[CoversClass(ArrayOf::class)]
final class AbstractModelMappingTest extends TestCase
{
    #[Test]
    public function itCorrectlyCastsArrayOfEnums(): void
    {
        $result = DummyModelForMapping::fromArray([
            'name' => 'Test With Enums',
            'update_types' => ['message_created', 'bot_started'],
        ]);

        $this->assertInstanceOf(DummyModelForMapping::class, $result);
        $this->assertIsArray($result->updateTypes);
        $this->assertCount(2, $result->updateTypes);
        $this->assertInstanceOf(UpdateType::class, $result->updateTypes[0]);
        $this->assertSame(UpdateType::MessageCreated, $result->updateTypes[0]);
        $this->assertSame(UpdateType::BotStarted, $result->updateTypes[1]);
    }

    #[Test]
    public function itCorrectlyCastsArrayOfModels(): void
    {
        $result = DummyModelForMapping::fromArray([
            'name' => 'Test With Models',
            'child_models' => [
                ['value' => 'child 1'],
                ['value' => 'child 2'],
            ],
        ]);

        $this->assertIsArray($result->childModels);
        $this->assertCount(2, $result->childModels);
        $this->assertInstanceOf(DummyChildModel::class, $result->childModels[0]);
        $this->assertSame('child 1', $result->childModels[0]->value);
    }

    #[Test]
    public function itReturnsRawArrayWhenAttributeIsMissing(): void
    {
        $result = DummyModelForMapping::fromArray(['untyped_array' => ['a', 'b', 'c']]);

        $this->assertIsArray($result->untypedArray);
        $this->assertSame(['a', 'b', 'c'], $result->untypedArray);
    }

    #[Test]
    public function itHandlesEmptyArraysCorrectly(): void
    {
        $result = DummyModelForMapping::fromArray([
            'name' => 'Test with Empty',
            'update_types' => [],
        ]);

        $this->assertIsArray($result->updateTypes);
        $this->assertEmpty($result->updateTypes);
    }

    #[Test]
    public function itHandlesNullForNullableArray(): void
    {
        $result = DummyModelForMapping::fromArray(['child_models' => null]);

        $this->assertNull($result->childModels);
    }

    #[Test]
    public function itCorrectlyCastsInteger(): void
    {
        $result = DummyModelForMapping::fromArray(['untyped_int' => 123]);

        $this->assertIsInt($result->untypedInt);
        $this->assertSame(123, $result->untypedInt);
    }

    #[Test]
    public function itCorrectlyCastsFloat(): void
    {
        $result = DummyModelForMapping::fromArray(['untyped_float' => 1.23]);

        $this->assertIsFloat($result->untypedFloat);
        $this->assertSame(1.23, $result->untypedFloat);
    }

    #[Test]
    public function itCorrectlyCastsBoolean(): void
    {
        $result = DummyModelForMapping::fromArray(['untyped_bool' => true]);

        $this->assertIsBool($result->untypedBool);
        $this->assertTrue($result->untypedBool);
    }

    #[Test]
    public function itReturnsValueAsIsForUnmanagedObjectType(): void
    {
        $externalObject = new stdClass();
        $externalObject->data = 'some value';

        $rawData = [
            'name' => 'Test With External Object',
            'external_object' => $externalObject,
        ];

        $result = ModelWithExternalObject::fromArray($rawData);

        $this->assertInstanceOf(ModelWithExternalObject::class, $result);
        $this->assertSame($externalObject, $result->externalObject);
        $this->assertSame('some value', $result->externalObject->data);
    }

    #[Test]
    public function castArrayHandlesNonArrayValueForArrayProperty(): void
    {
        $rawData = [
            'name' => 'Test with scalar instead of array',
            'tags' => 'single_tag_value',
        ];

        $result = DummyModelForArrayCast::fromArray($rawData);

        $this->assertInstanceOf(DummyModelForArrayCast::class, $result);
        $this->assertIsArray($result->tags);
        $this->assertSame(['single_tag_value'], $result->tags);
    }

    #[Test]
    public function castArrayReturnsArrayAsIsForUnmanagedObjectTypesInArrayOf(): void
    {
        $items = [
            (object)['id' => 1, 'name' => 'Item A'],
            (object)['id' => 2, 'name' => 'Item B'],
        ];
        $rawData = [
            'name' => 'Test with unmanaged objects',
            'items' => $items,
        ];

        $result = ModelWithUnmanagedArray::fromArray($rawData);

        $this->assertInstanceOf(ModelWithUnmanagedArray::class, $result);
        $this->assertIsArray($result->items);
        $this->assertSame($items, $result->items);
        $this->assertSame('Item A', $result->items[0]->name);
    }

    #[Test]
    public function toArraySkipsUninitializedProperties(): void
    {
        $reflection = new \ReflectionClass(DummyModelForUninitializedProperty::class);
        $instance = $reflection->newInstanceWithoutConstructor();

        $initializedProp = $reflection->getProperty('initializedProp');
        $initializedProp->setValue($instance, 'I have a value');

        $resultArray = $instance->toArray();

        $expectedArray = [
            'initialized_prop' => 'I have a value',
        ];

        $this->assertEquals($expectedArray, $resultArray);
        $this->assertArrayNotHasKey('uninitialized_prop', $resultArray);
    }

    #[Test]
    public function castValueReturnsValueAsIsForUnmanagedScalarAssignedToObjectType(): void
    {
        $dateString = '2025-08-26';

        $reflectionClass = new ReflectionClass(ModelForUnmanagedType::class);
        $abstractModelReflection = $reflectionClass->getParentClass();
        $castValueMethod = $abstractModelReflection->getMethod('castValue');
        $castValueMethod->setAccessible(true);

        $property = $reflectionClass->getProperty('eventDate');

        $result = $castValueMethod->invoke(null, $dateString, $property);

        $this->assertSame($dateString, $result);
    }
}

final readonly class ModelForUnmanagedType extends AbstractModel
{
    public function __construct(public DateTimeInterface $eventDate)
    {
    }
}

final readonly class DummyModelForUninitializedProperty extends AbstractModel
{
    public string $initializedProp;
    public int $uninitializedProp;
}

final readonly class DummyModelForArrayCast extends AbstractModel
{
    public function __construct(
        public ?string $name,
        public ?array $tags,
    ) {
    }
}

final readonly class ModelWithUnmanagedArray extends AbstractModel
{
    public function __construct(
        public ?string $name,
        #[ArrayOf(stdClass::class)]
        public ?array $items,
    ) {
    }
}

final readonly class ModelWithExternalObject extends AbstractModel
{
    public function __construct(
        public ?string $name,
        public ?stdClass $externalObject,
    ) {
    }
}

final readonly class DummyModelForMapping extends AbstractModel
{
    public function __construct(
        public ?string $name,
        #[ArrayOf(UpdateType::class)]
        public ?array $updateTypes,
        #[ArrayOf(DummyChildModel::class)]
        public ?array $childModels,
        public ?array $untypedArray,
        public ?int $untypedInt,
        public ?float $untypedFloat,
        public ?bool $untypedBool,
    ) {
    }
}

final readonly class DummyChildModel extends AbstractModel
{
    public function __construct(public string $value)
    {
    }
}
