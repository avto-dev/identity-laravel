<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Helpers;

/**
 * @internal
 */
class Transliterator
{
    protected const LATIN_TO_CYRILLIC_TRANSLITERATE_MAP = [
        'A' => 'А', 'a' => 'а',
        'B' => 'В', 'b' => 'в',
        'E' => 'Е', 'e' => 'е',
        'K' => 'К', 'k' => 'к',
        'M' => 'М', 'm' => 'м',
        'H' => 'Н', 'h' => 'н',
        'O' => 'О', 'o' => 'о',
        'P' => 'Р', 'p' => 'р',
        'C' => 'С', 'c' => 'с',
        'T' => 'Т', 't' => 'т',
        'Y' => 'У', 'y' => 'у',
        'X' => 'Х', 'x' => 'х',
    ];


    /**
     * Производит транслитерацию латинских символов в кириллические аналоги.
     *
     * @param string $string
     *
     * @return string
     */
    public static function detransliterateString(string $string): string
    {
        return \strtr($string, self::LATIN_TO_CYRILLIC_TRANSLITERATE_MAP);
    }
}
