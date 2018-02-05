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

    /**
     * {@inheritdoc}
     */
    public function testConstructor()
    {
        $class_name = $this->getClassName();

        $this->assertEquals($value = 'fooooooobar', (string) new $class_name($value, true));
        $this->assertEquals($value, (string) new $class_name($value, false));
    }

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
}
