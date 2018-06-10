<?php

namespace AvtoDev\IDEntity\Types;

use Exception;

/**
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
        try {
            return (string) $value;
        } catch (Exception $e) {
            // Do nothing
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidateCallbacks()
    {
        return function () {
            return false;
        };
    }
}
