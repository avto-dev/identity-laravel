<?php

namespace AvtoDev\IDEntity\Types;

use Exception;
use Illuminate\Support\Str;
use AvtoDev\IDEntity\Helpers\Transliterator;

/**
 * Class IDEntityGrz.
 *
 * Идентификатор - номер ГРЗ.
 */
class IDEntityGrz extends AbstractTypedIDEntity
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return static::ID_TYPE_GRZ;
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
            $value = preg_replace('~[^' . 'АВЕКМНОРСТУХ' . 'ABEKMHOPCTYX' . '0-9]~u', '', $value);

            // Производим замену латинских аналогов на кириллические (обратная транслитерация). Не прогоняю по всем
            // возможными символам, так как регулярка что выше всё кроме них как раз и удаляет
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
                return $this->validateWithValidatorRule($value, 'required|string|grz_code');
            },
        ];
    }
}
