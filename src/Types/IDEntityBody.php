<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use Exception;
use Illuminate\Support\Str;
use AvtoDev\IDEntity\Helpers\Normalizer;
use AvtoDev\IDEntity\Helpers\Transliterator;
use AvtoDev\ExtendedLaravelValidator\Extensions\BodyCodeValidatorExtension;

class IDEntityBody extends AbstractTypedIDEntity
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return static::ID_TYPE_BODY;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        try {
            // Заменяем множественные пробелы - одиночными
            $value = (string) \preg_replace('~\s+~u', ' ', \trim((string) $value));

            // Нормализуем символы дефиса
            $value = Normalizer::normalizeDashChar($value);

            // Заменяем множественные дефисы - одиночными
            $value = (string) \preg_replace('~-+~', '-', $value);

            // Заменяем идущие подряд тире и пробел (в любом порядке) на одиночное тире
            $value = (string) \preg_replace('~\s*-\s*~', '-', $value);

            // Производим замену кириллических символов на латинские аналоги
            $value = Transliterator::transliterateString(Str::upper($value), true);

            // Удаляем все символы, кроме разрешенных
            $value = (string) \preg_replace('~[^A-Z0-9\- ]~u', '', $value);

            return $value;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        /** @var BodyCodeValidatorExtension $validator */
        $validator = static::getContainer()->make(BodyCodeValidatorExtension::class);

        return \is_string($this->value) && $validator->passes('', $this->value);
    }
}
