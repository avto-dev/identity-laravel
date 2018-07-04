<?php

namespace AvtoDev\IDEntity\Tests\Mocks\Types;

class IDEntityValidCantAutodetectMock extends AbstractTypeIDEntityMock
{
    const TYPE = 'VALID_CANT_AUTODETECT';

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
}
