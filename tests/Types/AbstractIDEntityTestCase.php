<?php

namespace AvtoDev\IDEntity\Tests\Types;

use stdClass;
use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Tests\AbstractTestCase;
use AvtoDev\IDEntity\Types\AbstractTypedIDEntity;
use AvtoDev\IDEntity\Types\TypedIDEntityInterface;

/**
 * Class AbstractIDEntityTestCase.
 */
abstract class AbstractIDEntityTestCase extends AbstractTestCase
{
    /**
     * @var AbstractTypedIDEntity
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $class_name = $this->getClassName();

        $this->instance = new $class_name($this->getValidValue());
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->instance);

        parent::tearDown();
    }

    /**
     * Тест конструктора.
     *
     * @return void
     */
    public function testConstructor()
    {
        $class_name = $this->getClassName();

        /** @var AbstractTypedIDEntity $instance */
        $instance = new $class_name($value = $this->getValidValue());
        $this->assertEquals($instance->getValue(), $value);
    }

    /**
     * Тест наследования и реализуемых интерфейсов.
     *
     * @return void
     */
    public function testInstances()
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
    public function testToString()
    {
        $this->assertEquals($this->instance->getValue(), (string) $this->instance);
    }

    /**
     * Тест метода 'make'.
     *
     * @return void
     */
    public function testMakeMethod()
    {
        $instance = $this->instance;

        $this->assertEquals($instance, $instance::make($instance->getValue()));
    }

    /**
     * Тест метода 'is'.
     *
     * @return void
     */
    public function testIsMethod()
    {
        $instance = $this->instance;

        $this->assertEquals($instance->isValid(), $instance::is($instance->getValue()));
    }

    /**
     * Тест метода 'getValue'.
     *
     * @return void
     */
    public function testGetAndSetValue()
    {
        $this->assertInstanceOf(
            $this->getClassName(),
            $this->instance->setValue($value = 'foo bar', false)
        );

        $this->assertEquals($this->instance->getValue(), $value);
    }

    /**
     * Тест метода 'getType'.
     *
     * @return void
     */
    abstract public function testGetType();

    /**
     * Тест методов преобразования объекта в массив и json.
     *
     * @return void
     */
    public function testToArrayAndToJson()
    {
        $this->assertEquals($array = [
            'value' => $this->instance->getValue(),
            'type'  => $this->instance->getType(),
        ], $this->instance->toArray());

        $this->assertEquals(json_encode($array), $this->instance->toJson());
    }

    /**
     * Тест метода собственной валидации.
     *
     * @return void
     */
    abstract public function testIsValid();

    /**
     * Тест метода нормализации значения.
     *
     * @return void
     */
    abstract public function testNormalize();

    /**
     * Тест работы метода нормализации с некорректными данными на входе.
     *
     * @return null
     */
    public function testNormalizeWithInvalidInputData()
    {
        $invalid_values = [
            new stdClass,
            [],
            function () {},
        ];

        $instance = $this->instance;

        foreach ($invalid_values as $value) {
            $this->assertNull($instance::normalize($value));
        }
    }
    
    /**
     * Возвращает имя тестируемого класса типизированной сущности.
     *
     * @return string
     */
    abstract protected function getClassName();

    /**
     * Возвращает валидное значение сущности.
     *
     * @return string
     */
    abstract protected function getValidValue();
}
