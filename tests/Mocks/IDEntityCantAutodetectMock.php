<?php

namespace AvtoDev\IDEntity\Tests\Mocks;

use AvtoDev\IDEntity\Types\AbstractTypedIDEntity;

class IDEntityCantAutodetectMock extends AbstractTypedIDEntity
{
    const TYPE = 'CANT_AUTODETECT';

    /**
     * {@inheritdoc}
     */
    protected $can_autodetect = false;

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return self::TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidateCallbacks()
    {
        return function () {
            return true;
        };
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value)
    {
        return $value;
    }
}
