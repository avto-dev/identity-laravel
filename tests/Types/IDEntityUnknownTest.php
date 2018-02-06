<?php

namespace AvtoDev\IDEntity\Tests\Types;

use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Types\IDEntityUnknown;

/**
 * Class IDEntityUnknownTest.
 */
class IDEntityUnknownTest extends AbstractIDEntityTestCase
{
    /**
     * {@inheritdoc}
     */
    public function testGetType()
    {
        $this->assertEquals(IDEntity::ID_TYPE_UNKNOWN, $this->instance->getType());
    }

    /**
     * {@inheritdoc}
     */
    public function testIsValid()
    {
        $this->assertFalse($this->instance->isValid());
    }

    /**
     * {@inheritdoc}
     */
    public function testNormalize()
    {
        $instance = $this->instance;

        $this->assertEquals($value = ' foo bar ', $instance::normalize($value));
    }

    /**
     * {@inheritdoc}
     */
    protected function getClassName()
    {
        return IDEntityUnknown::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValue()
    {
        return 'foo bar';
    }
}
