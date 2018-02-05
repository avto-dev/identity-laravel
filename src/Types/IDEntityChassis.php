<?php

namespace AvtoDev\IDEntity\Types;

use Exception;
use AvtoDev\IDEntity\Helpers\Normalizer;
use AvtoDev\IDEntity\Helpers\Transliterator;

/**
 * Class IDEntityChassis.
 *
 * Идентификатор - номер шасси.
 */
class IDEntityChassis extends AbstractTypedIDEntity
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return static::ID_TYPE_CHASSIS;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value)
    {
        try {
            // Заменяем множественные пробелы - одиночными
            $value = preg_replace('~\s+~u', ' ', trim((string) $value));

            // Заменяем пробелы - дефисами
            $value = preg_replace('~[[:space:]]+~', '-', $value);

            // Номализуем символы дефиса
            $value = Normalizer::normalizeDashChar($value);

            // Заменяем множественные дефисы - одиночными
            $value = preg_replace('~\-+~', '-', $value);

            // Производим замену кириллических символов на латинские аналоги
            $value = Transliterator::uppercaseAndSafeTransliterate($value);

            // Удаляем все символы, кроме разрешенных
            $value = preg_replace('~[^A-Z0-9\-]~u', '', $value);

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
                return $this->validateWithValidatorRule($value, 'required|string|chassis_code');
            },
        ];
    }
}
