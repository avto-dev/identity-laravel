<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use Illuminate\Support\Str;
use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\IDEntityInterface;
use AvtoDev\IDEntity\Tests\AbstractTestCase;
use AvtoDev\IDEntity\Types\AbstractTypedIDEntity;
use AvtoDev\IDEntity\Types\TypedIDEntityInterface;

abstract class AbstractIDEntityTestCase extends AbstractTestCase
{
    /**
     * @var string|null
     */
    protected $expected_type;

    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $entity = $this->entityFactory();

        foreach ([IDEntity::class, IDEntityInterface::class, TypedIDEntityInterface::class] as $expects) {
            $this->assertInstanceOf($expects, $entity);
        }
    }

    /**
     * @return void
     */
    public function testToString(): void
    {
        $this->assertSame(($entity = $this->entityFactory())->getValue(), (string) $entity);
    }

    /**
     * @return void
     */
    public function testGetMaskedValue(): void
    {
        $entity = $this->entityFactory();

        $entity->setValue('foo_blablabla_bar', false);
        $this->assertSame('foo***********bar', $entity->getMaskedValue());

        $entity->setValue('foo_bla_bar', false);
        $this->assertSame('foo^^^^^bar', $entity->getMaskedValue(3, 3, '^foo'));

        $entity->setValue('foo_blablabla_bar', false);
        $this->assertSame('fo+++++++++++_bar', $entity->getMaskedValue(2, 4, '+'));

        $entity->setValue('foo_blablabla_bar', false);
        $this->assertSame('foo_blablabla_bar', $entity->getMaskedValue(20, 20));

        $entity->setValue('foo_blablabla_bar', false);
        $this->assertSame('*****************', $entity->getMaskedValue(0, 0));
    }

    /**
     * @return void
     */
    public function testMakeMethod(): void
    {
        $this->assertSame(
            \serialize($entity = $this->entityFactory()),
            \serialize($entity::make($entity->getValue()))
        );
    }

    /**
     * @return void
     */
    public function testIsMethod(): void
    {
        foreach ($this->getValidValues() as $valid_value) {
            $entity = $this->entityFactory($valid_value);

            $this->assertSame($entity->isValid(), $entity::is($entity->getValue()));
        }

        $this->assertFalse($this->entityFactory()::is(Str::random(64)));
    }

    /**
     * @return void
     */
    public function testGetAndSetValueWithoutNormalization(): void
    {
        $entity = $this->entityFactory();

        $this->assertInstanceOf(\get_class($entity), $entity->setValue($value = 'foo bar', false));
        $this->assertSame($entity->getValue(), $value);
    }

    /**
     * @return void
     */
    public function testGetType(): void
    {
        if (! \is_string($this->expected_type)) {
            $this->markTestIncomplete(\sprintf('Test in %s does not have declared expected entity type', __CLASS__));
        }

        $this->assertSame($this->expected_type, $this->entityFactory()->getType());
    }

    /**
     * @return void
     */
    public function testToArray(): void
    {
        $entity = $this->entityFactory();

        $this->assertSame([
            'value' => $entity->getValue(),
            'type'  => $entity->getType(),
        ], $entity->toArray());
    }

    /**
     * @return void
     */
    public function testToJson(): void
    {
        $entity = $this->entityFactory();

        $this->assertSame(\json_encode([
            'value' => $entity->getValue(),
            'type'  => $entity->getType(),
        ]), $entity->toJson());
    }

    /**
     * @return void
     */
    public function testToJsonWithExceptions(): void
    {
        $entity = $this->entityFactory();
        $this->expectException(\TypeError::class);
        $entity->toJson([]);

        $this->expectException(\TypeError::class);
        $entity->toJson("string");
    }

    /**
     * @return void
     */
    public function testIsValid(): void
    {
        $entity = $this->entityFactory();

        foreach ($this->getValidValues() as $value) {
            $this->assertTrue($entity->setValue($value)->isValid(), "Value [{$value}] must be valid");
        }

        foreach ($this->getInvalidValues() as $value) {
            $this->assertFalse($entity->setValue($value)->isValid(), "Value [{$value}] must be invalid");
        }
    }

    /**
     * @return void
     */
    abstract public function testNormalize(): void;

    /**
     * @return void
     */
    public function testNormalizeWithInvalidInputData(): void
    {
        $invalid_values = [
            new \stdClass,
            [],
            static function (): void {
            },
        ];

        foreach ($invalid_values as $value) {
            $this->assertNull($this->entityFactory()::normalize($value));
        }
    }

    /**
     * @return void
     */
    public function testCanAutodetect(): void
    {
        $this->assertTrue($this->entityFactory()->canBeAutoDetected());
    }

    /**
     * Tested entity factory.
     *
     * @param string|null $value
     *
     * @return AbstractTypedIDEntity
     */
    abstract protected function entityFactory(?string $value = null);

    /**
     * @return string[]
     */
    abstract protected function getValidValues(): array;

    /**
     * @return string[]
     */
    abstract protected function getInvalidValues(): array;
}
