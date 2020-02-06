<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use Illuminate\Support\Str;
use AvtoDev\IDEntity\IDEntityInterface;
use AvtoDev\IDEntity\Types\IDEntityUnknown;

/**
 * @covers \AvtoDev\IDEntity\Types\IDEntityUnknown
 */
class IDEntityUnknownTest extends AbstractIDEntityTestCase
{
    /**
     * @var string
     */
    protected $expected_type = IDEntityInterface::ID_TYPE_UNKNOWN;

    /**
     * {@inheritdoc}
     */
    public function testNormalize(): void
    {
        $entity = $this->entityFactory();

        $this->assertSame($value = ' foo bar ', $entity::normalize($value));
    }

    /**
     * {@inheritDoc}
     */
    public function testIsValid(): void
    {
        $entity = $this->entityFactory();

        foreach ($this->getValidValues() as $value) {
            $this->assertFalse($entity->setValue($value)->isValid());
        }

        foreach ($this->getInvalidValues() as $value) {
            $this->assertFalse($entity->setValue($value)->isValid());
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function entityFactory(?string $value = null): IDEntityUnknown
    {
        return new IDEntityUnknown($value ?? $this->getValidValues()[0]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValues(): array
    {
        return [
            Str::random(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function getInvalidValues(): array
    {
        return [
            Str::random(),
            Str::random(32),
            '',
            'foo',
            'foo bar',
        ];
    }
}
