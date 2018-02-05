<?php

namespace AvtoDev\IDEntity\Types;

use Exception;
use Illuminate\Support\Str;
use AvtoDev\IDEntity\Helpers\Transliterator;

/**
 * Class IDEntitySts.
 *
 * Идентификатор - номер СТС.
 */
class IDEntitySts extends AbstractTypedIDEntity
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return static::ID_TYPE_STS;
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
            $value = Transliterator::uppercaseAndSafeDeTransliterate($value);

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
            function ($value) {
                return $this->validateWithValidatorRule($value, 'required|string|sts_code');
            },
        ];
    }
}
