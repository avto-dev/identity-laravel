<?php

namespace AvtoDev\IDEntity\Types;

use Exception;
use Illuminate\Support\Str;
use AvtoDev\IDEntity\Helpers\Normalizer;
use AvtoDev\IDEntity\Helpers\Transliterator;

/**
 * Идентификатор - номер кузова.
 */
class IDEntityBody extends AbstractTypedIDEntity
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return static::ID_TYPE_BODY;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value)
    {
        try {
            // Заменяем множественные пробелы - одиночными
            $value = \preg_replace('~\s+~u', ' ', trim((string) $value));

            // Номализуем символы дефиса
            $value = (string) Normalizer::normalizeDashChar($value);

            // Заменяем множественные дефисы - одиночными
            $value = \preg_replace('~\-+~', '-', $value);

            // Заменяем идущие подряд тире и пробел (в любом порядке) на одиночное тире
            $value = \preg_replace('~\s*\-\s*~', '-', $value);

            // Производим замену кириллических символов на латинские аналоги
            $value = Transliterator::transliterateString(Str::upper($value), true);

            // Удаляем все символы, кроме разрешенных
            $value = \preg_replace('~[^A-Z0-9\- ]~u', '', $value);

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
                return $this->validateWithValidatorRule($this->getValue(), 'required|string|body_code');
            },
        ];
    }
}
