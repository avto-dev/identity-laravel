<?php
declare(strict_types=1);

namespace AvtoDev\IDEntity\Helpers;

class Strings
{
    // Символы, которые являются признаком кириллического алфавита.
    protected const CYR_SPECIFIC_CHARS = [
        'Б', 'Г', 'Д', 'Ж', 'Ё', 'З', 'И', 'Й', 'Л', 'П', 'Ф', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
    ];

    /**
     * Удаляет все НЕ буквы и НЕ цифры из строки.
     *
     * @param string $value
     * @return string
     */
    public static function onlyAlfaNumeric(string $value): string
    {
        return (string)\preg_replace('/[^\p{L}\p{N}]/u', '', $value);
    }

    /**
     * Заменяет символы в строке по карте $replacements.
     *
     * @param string $value
     * @param array<string|int, string|int> $replacements
     * @return string
     */
    public static function replaceByMap(string $value, array $replacements): string
    {
        return \str_replace(array_keys($replacements), array_values($replacements), $value);
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function hasSpecificCyrillicChars(string $value): bool
    {
        $value = mb_strtoupper($value, 'UTF-8');

        foreach (self::CYR_SPECIFIC_CHARS as $char) {
            if (\str_contains($value, $char)) {
                return true;
            }
        }

        return false;
    }
}
