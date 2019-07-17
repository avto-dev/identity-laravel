<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Types\IDEntityUnknown;

class IDEntityUnknownTest extends AbstractIDEntityTestCase
{
    /**
     * @var IDEntityUnknown
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    public function testGetType(): void
    {
        $this->assertEquals(IDEntity::ID_TYPE_UNKNOWN, $this->instance->getType());
    }

    /**
     * {@inheritdoc}
     */
    public function testIsValid(): void
    {
        $this->assertFalse($this->instance->isValid());
    }

    /**
     * {@inheritdoc}
     */
    public function testNormalize(): void
    {
        $this->assertEquals($value = ' foo bar ', $this->instance::normalize($value));
    }

    /**
     * {@inheritdoc}
     */
    protected function getClassName(): string
    {
        return IDEntityUnknown::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValue(): string
    {
        return 'foo bar';
    }
}
