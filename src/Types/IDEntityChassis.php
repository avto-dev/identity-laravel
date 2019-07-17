<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use Exception;
use Illuminate\Support\Str;
use AvtoDev\IDEntity\Helpers\Normalizer;
use AvtoDev\IDEntity\Helpers\Transliterator;
use AvtoDev\ExtendedLaravelValidator\Extensions\ChassisCodeValidatorExtension;

class IDEntityChassis extends AbstractTypedIDEntity
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return static::ID_TYPE_CHASSIS;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        try {
            // Заменяем множественные пробелы - одиночными
            $value = (string) \preg_replace('~\s+~u', ' ', trim((string) $value));

            // Нормализуем символы дефиса
            $value = Normalizer::normalizeDashChar($value);

            // Производим замену кириллических символов на латинские аналоги
            $value = Transliterator::transliterateString(Str::upper($value), true);

            // Удаляем все символы, кроме разрешенных
            $value = (string) \preg_replace('~[^A-Z0-9\- ]~u', '', $value);

            // Заменяем множественные дефисы - одиночными
            $value = (string) \preg_replace('~\-+~', '-', $value);

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
        /** @var ChassisCodeValidatorExtension $validator */
        $validator = static::getContainer()->make(ChassisCodeValidatorExtension::class);

        return \is_string($this->value) && $validator->passes('', $this->value);
    }
}
