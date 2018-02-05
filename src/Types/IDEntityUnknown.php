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
    protected function getValidateCallbacks()
    {
        return null;
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
}
