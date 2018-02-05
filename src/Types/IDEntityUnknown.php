<?php

namespace AvtoDev\IDEntity\Types;

/**
 * Class IDEntityUnknown.
 *
 * Неизвестный идентификатор.
 */
class IDEntityUnknown extends AbstractTypedIDEntity
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return static::ID_TYPE_UNKNOWN;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value)
    {
        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidateCallbacks()
    {
    }
}
