<?php

namespace AvtoDev\IDEntity\Types;

use Exception;
use Illuminate\Support\Str;
use AvtoDev\IDEntity\Helpers\Transliterator;

/**
 * Class IDEntityPts.
 *
 * Идентификатор - номер ПТС.
 */
class IDEntityPts extends AbstractTypedIDEntity
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return static::ID_TYPE_PTS;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value)
    {
        try {
            // Переводим в верхний регистр + trim
            $value = Str::upper(trim((string) $value));

            // Удаляем все символы, кроме разрешенных
            $value = preg_replace('~[^' . 'АБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЫЭЮЯ' . 'A-Z' . '0-9]~u', '', $value);

            // Производим замену латинских аналогов на кириллические (обратная транслитерация)
            $value = Transliterator::detransliterateString(Str::upper($value), true);

            return $value;
        } catch (Exception $e) {
            return;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidateCallbacks()
    {
        return [
            function ($value) {
                return $this->validateWithValidatorRule($value, 'required|string|pts_code');
            },
        ];
    }
}
