<?php
declare(strict_types=1);

namespace AvtoDev\IDEntity\Helpers;

class Strings
{
    // Символы, которые являются признаком кириллического Алфавита.
    protected const CYR_SPECIFIC_CHARS = [
        'Б', 'Г', 'Д', 'Ж', 'Ё', 'З', 'И', 'Й', 'Л', 'П', 'Ф', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
    ];

    /**
     * Удаляет все НЕ буквы и НЕ цифры из строки.
     *
     * @param string $value
     * @return string
     */
    public static function removeNonAlphanumericChars(string $value): string
    {
        return (string)\preg_replace('/[^\p{L}\p{N}]/u', '', $value);
    }

    /**
     * Заменяет символы в строке по карте $replacements. Ключи и значения $replacements должны быть строками.
     *
     * @param string $value
     * @param array<string, string> $replacements
     * @return string
     */
    public static function replaceByMap(string $value, array $replacements): string
    {
        return \str_replace(array_keys($replacements), array_values($replacements), $value);
    }

    /**
     * @param string $upper_value Строка в верхнем регистре.
     * @return bool
     */
    public static function hasSpecificCyrillicChars(string $upper_value): bool
    {
        foreach (self::CYR_SPECIFIC_CHARS as $char) {
            if (\str_contains($upper_value, $char)) {
                return true;
            }
        }

        return false;
    }
}
