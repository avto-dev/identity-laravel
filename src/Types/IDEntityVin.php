<?php

namespace AvtoDev\IDEntity\Types;

use Exception;
use AvtoDev\IDEntity\Helpers\Transliterator;

/**
 * Class IDEntityVin.
 *
 * Идентификатор - VIN код.
 */
class IDEntityVin extends AbstractTypedIDEntity
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return static::ID_TYPE_VIN;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidateCallbacks()
    {
        return [
            function ($value) {
                return $this->validateWithValidatorRule($value, 'required|string|vin_code');
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value)
    {
        try {
            // Производим замену кириллических символов на латинские аналоги.
            $value = Transliterator::uppercaseAndSafeTransliterate($value);

            // Латинская "O" заменяется на ноль
            $value = str_replace('O', '0', $value);

            // Удаляем все символы, кроме разрешенных
            $value = preg_replace('~[^ABCDEFGHJKLMNPRSTUVWXYZ0-9]~u', '', $value);

            return $value;
        } catch (Exception $e) {
            // Do nothing
        }

        return null;
    }
}
