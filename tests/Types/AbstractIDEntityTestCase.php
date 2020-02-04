<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use stdClass;
use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Tests\AbstractTestCase;
use AvtoDev\IDEntity\Types\AbstractTypedIDEntity;
use AvtoDev\IDEntity\Types\TypedIDEntityInterface;

abstract class AbstractIDEntityTestCase extends AbstractTestCase
{
    /**
     * @var AbstractTypedIDEntity
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $class_name = $this->getClassName();

        $this->instance = new $class_name($this->getValidValue());
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        unset($this->instance);

        parent::tearDown();
    }

    /**
     * Тест конструктора.
     *
     * @return void
     */
    public function testConstructor(): void
    {
        $class_name = $this->getClassName();

        /** @var AbstractTypedIDEntity $instance */
        $instance = new $class_name($value = $this->getValidValue());
        $this->assertSame($instance->getValue(), $value);
    }

    /**
     * Тест наследования и реализуемых интерфейсов.
     *
     * @return void
     */
    public function testInstances(): void
    {
        foreach ([IDEntity::class, TypedIDEntityInterface::class] as $class_name) {
            $this->assertInstanceOf($class_name, $this->instance);
        }
    }

    /**
     * Тест метода конвертации объекта в строку.
     *
     * @return void
     */
    public function testToString(): void
    {
        $this->assertSame($this->instance->getValue(), (string) $this->instance);
    }

    /**
     * Тест метода 'getMaskedValue'.
     *
     * @return void
     */
    public function testGetMaskedValue(): void
    {
        $this->instance->setValue('foo_blablabla_bar', false);
        $this->assertSame('foo***********bar', $this->instance->getMaskedValue());

        $this->instance->setValue('foo_bla_bar', false);
        $this->assertSame('foo^^^^^bar', $this->instance->getMaskedValue(3, 3, '^foo'));

        $this->instance->setValue('foo_blablabla_bar', false);
        $this->assertSame('fo+++++++++++_bar', $this->instance->getMaskedValue(2, 4, '+'));

        $this->instance->setValue('foo_blablabla_bar', false);
        $this->assertSame('foo_blablabla_bar', $this->instance->getMaskedValue(20, 20));

        $this->instance->setValue('foo_blablabla_bar', false);
        $this->assertSame('*****************', $this->instance->getMaskedValue(0, 0));
    }

    /**
     * Тест метода 'make'.
     *
     * @return void
     */
    public function testMakeMethod(): void
    {
        $this->assertSame(\serialize($this->instance), \serialize($this->instance::make($this->instance->getValue())));
    }

    /**
     * Тест метода 'is'.
     *
     * @return void
     */
    public function testIsMethod(): void
    {
        $this->assertSame($this->instance->isValid(), $this->instance::is($this->instance->getValue()));

        // Второй аргумент для 'is' игнорируется
        $this->assertSame($this->instance->isValid(), $this->instance::is($this->instance->getValue(), [123, null]));
        $this->assertSame($this->instance->isValid(), $this->instance::is($this->instance->getValue(), ['foo']));
        $this->assertSame($this->instance->isValid(), $this->instance::is($this->instance->getValue(), [IDEntity::ID_TYPE_VIN]));
    }

    /**
     * Тест метода 'getValue'.
     *
     * @return void
     */
    public function testGetAndSetValue(): void
    {
        $this->assertInstanceOf(
            $this->getClassName(),
            $this->instance->setValue($value = 'foo bar', false)
        );

        $this->assertSame($this->instance->getValue(), $value);
    }

    /**
     * Тест метода 'getType'.
     *
     * @return void
     */
    abstract public function testGetType(): void;

    /**
     * Тест методов преобразования объекта в массив и json.
     *
     * @return void
     */
    public function testToArrayAndToJson(): void
    {
        $this->assertSame($array = [
            'value' => $this->instance->getValue(),
            'type'  => $this->instance->getType(),
        ], $this->instance->toArray());

        $this->assertSame(json_encode($array), $this->instance->toJson());
    }

    /**
     * Тест метода собственной валидации.
     *
     * @return void
     */
    abstract public function testIsValid(): void;

    /**
     * Тест метода нормализации значения.
     *
     * @return void
     */
    abstract public function testNormalize(): void;

    /**
     * Тест работы метода нормализации с некорректными данными на входе.
     *
     * @return void
     */
    public function testNormalizeWithInvalidInputData(): void
    {
        $invalid_values = [
            new stdClass,
            [],
            function (): void {
            },
        ];

        foreach ($invalid_values as $value) {
            $this->assertNull($this->instance::normalize($value));
        }
    }

    /**
     * Проверяем, что по умолчанию идентификатор доступен для автоматического определения.
     */
    public function testCanAutodetect(): void
    {
        $this->assertTrue($this->instance->canBeAutoDetected());
    }

    /**
     * Возвращает имя тестируемого класса типизированной сущности.
     *
     * @return string
     */
    abstract protected function getClassName(): string;

    /**
     * Возвращает валидное значение сущности.
     *
     * @return string
     */
    abstract protected function getValidValue(): string;
}
