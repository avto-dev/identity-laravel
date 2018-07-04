<?php

namespace AvtoDev\IDEntity\Tests\Mocks\Types;

use AvtoDev\IDEntity\Types\AbstractTypedIDEntity;

abstract class AbstractTypeIDEntityMock extends AbstractTypedIDEntity
{
    /**
     * {@inheritdoc}
     */
    public static function normalize($value)
    {
        return $value;
    }
}
