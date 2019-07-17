<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use Exception;
use Illuminate\Support\Str;
use AvtoDev\IDEntity\Helpers\Transliterator;
use AvtoDev\ExtendedLaravelValidator\Extensions\StsCodeValidatorExtension;

class IDEntitySts extends AbstractTypedIDEntity
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return static::ID_TYPE_STS;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        try {
            // Переводим в верхний регистр + trim
            $value = Str::upper(\trim((string) $value));

            // Удаляем все символы, кроме разрешенных
            $value = (string) \preg_replace('~[^' . 'АБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЫЭЮЯ' . 'A-Z' . '0-9]~u', '', $value);

            // Производим замену латинских аналогов на кириллические (обратная транслитерация)
            $value = Transliterator::detransliterateString($value, true);

            return $value;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidateCallbacks()
    {
        return function (): bool {
            /** @var StsCodeValidatorExtension $validator */
            $validator = static::getContainer()->make(StsCodeValidatorExtension::class);

            return \is_string($this->value) && $validator->passes('', $this->value);
        };
    }
}
