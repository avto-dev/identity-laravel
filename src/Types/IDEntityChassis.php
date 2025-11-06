<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use AvtoDev\ExtendedLaravelValidator\Extensions\ChassisCodeValidatorExtension;
use Illuminate\Support\Str;

class IDEntityChassis extends AbstractTypedIDEntity
{
    protected const REPLACE_LATIN    = ['A', 'B', 'E', 'K', 'M', 'H', 'O', 'P', 'C', 'T', 'X', 'Y'];
    protected const REPLACE_CYRILLIC = ['А', 'В', 'Е', 'К', 'М', 'Н', 'О', 'Р', 'С', 'Т', 'Х', 'У'];
    protected const CYRILLIC_SPECIFIC = ['Б', 'Г', 'Д', 'Ё', 'Ж', 'З', 'И', 'Й', 'Л', 'П', 'Ф', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'];

    /**
     * {@inheritdoc}
     *
     * @return static
     */
    final public static function make(string $value, ?string $type = null): self
    {
        return new static($value);
    }

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
            $value = (string) \preg_replace('/[^\p{L}\p{N}]/u', '', $value);

            $value = \mb_strtoupper($value, 'UTF-8');

            if (Str::contains($value, self::CYRILLIC_SPECIFIC)) {
                return \str_replace(self::REPLACE_LATIN, self::REPLACE_CYRILLIC, $value);
            }

            return \str_replace(self::REPLACE_CYRILLIC, self::REPLACE_LATIN, $value);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        if (\is_string($this->value) && $this->value !== '') {
            /** @var ChassisCodeValidatorExtension $validator */
            $validator = static::getContainer()->make(ChassisCodeValidatorExtension::class);

            return $validator->passes('', $this->value);
        }

        return false;
    }
}
