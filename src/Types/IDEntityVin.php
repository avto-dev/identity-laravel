<?php

namespace AvtoDev\IDEntity\Types;

use Exception;
use Illuminate\Support\Str;
use AvtoDev\IDEntity\Helpers\Transliterator;

/**
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
    public static function normalize($value)
    {
        try {
            // Производим замену кириллических символов на латинские аналоги.
            $value = Transliterator::transliterateString(Str::upper($value), true);

            // Латинская "O" заменяется на ноль
            $value = \str_replace('O', '0', $value);

            // Удаляем все символы, кроме разрешенных
            $value = \preg_replace('~[^ABCDEFGHJKLMNPRSTUVWXYZ0-9]~u', '', $value);

            return $value;
        } catch (Exception $e) {
            // Do nothing
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidateCallbacks()
    {
        return [
            function () {
                return $this->validateWithValidatorRule($this->getValue(), 'required|string|vin_code');
            },
        ];
    }
}
