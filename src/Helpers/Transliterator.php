<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Helpers;

use Illuminate\Support\Str;

class Transliterator
{
    /**
     * Набор кириллических символов.
     *
     * @var string[]
     */
    protected static $cyr_chars = [
        'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р',
        'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'Х',

        'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р',
        'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', 'х',
    ];

    /**
     * Набор латинских символов для обратной транслитерации.
     *
     * @var string[]
     */
    protected static $latin_analogs = [
        'A', 'B', 'V', 'G', 'D', 'E', 'E', 'Zh', 'Z', 'I', 'I', 'K', 'L', 'M', 'N', 'O', 'P', 'R',
        'S', 'T', 'U', 'F', 'X', 'Ts', 'Ch', 'Sh', 'Shch', '', 'Y', '', 'E', 'Yu', 'Ya', 'H',

        'a', 'b', 'v', 'g', 'd', 'e', 'e', 'zh', 'z', 'i', 'i', 'k', 'l', 'm', 'n', 'o', 'p', 'r',
        's', 't', 'u', 'f', 'x', 'ts', 'ch', 'sh', 'shch', '', 'y', '', 'e', 'yu', 'ya', 'h',
    ];

    /**
     * Набор латинских символов для "безопасной" обратной транслитерации (без опаски что один символ будет
     * транслитерирован как два).
     *
     * @var string[]
     */
    protected static $latin_safe_analogs = [
        'A', 'B', 'B', 'G', 'D', 'E', 'E', 'J', 'Z', 'I', 'I', 'K', 'L', 'M', 'H', 'O', 'P', 'P',
        'C', 'T', 'Y', 'F', 'X', 'C', 'C', 'W', 'W', '', '', '', 'E', 'U', 'Y', 'X',

        'a', 'b', 'b', 'g', 'd', 'e', 'e', 'j', 'z', 'i', 'i', 'k', 'l', 'm', 'h', 'o', 'p', 'p',
        'c', 't', 'y', 'f', 'x', 'c', 'c', 'w', 'w', '', '', '', 'e', 'u', 'y', 'x',
    ];

    /**
     * Карта для замен символов, имеющих латинские аналоги.
     *
     * @var string[]
     */
    protected static $lite_cyr_map = [
        'А', 'В', 'Е', 'К', 'М', 'Н', 'О', 'Р', 'С', 'Т', 'У', 'Х',
        'а', 'в', 'е', 'к', 'м', 'н', 'о', 'р', 'с', 'т', 'у', 'х',
    ];

    /**
     * Обратная карта для замен символов, имеющих латинские аналоги.
     *
     * @var string[]
     */
    protected static $lite_latin_map = [
        'A', 'B', 'E', 'K', 'M', 'H', 'O', 'P', 'C', 'T', 'Y', 'X',
        'a', 'b', 'e', 'k', 'm', 'h', 'o', 'p', 'c', 't', 'y', 'x',
    ];

    /**
     * Производит транслитерацию только тех кириллических символов, что имеют латинские аналоги.
     *
     * @param string $string
     *
     * @return string
     */
    public static function transliterateLite(string $string): string
    {
        return \str_replace(static::$lite_cyr_map, static::$lite_latin_map, $string);
    }

    /**
     * Производит обратную транслитерацию (из латинских символов - в кириллические аналоги).
     *
     * @param string $string
     *
     * @return string
     */
    public static function detransliterateLite(string $string): string
    {
        return \str_replace(static::$lite_latin_map, static::$lite_cyr_map, $string);
    }

    /**
     * Транслитирирует строку.
     *
     * @param string $string
     * @param bool   $safe_mode "Безопасный" режим траслитерации, при котором **один** кириллический символ будет
     *                          гарантировано транслитирирован в **один** латинский
     *
     * @return string
     */
    public static function transliterateString(string $string, bool $safe_mode = false): string
    {
        if ($safe_mode === true) {
            $string = \str_replace(
                static::$cyr_chars,
                static::$latin_safe_analogs,
                $string
            );
        }

        return Str::ascii($string);
    }

    /**
     * Производит де-транслитерацию строки.
     *
     * @param string $string
     * @param bool   $after_safe_mode Указывает, что входящая строка была "безопасно" транслитирирована
     *
     * @return string
     */
    public static function detransliterateString(string $string, bool $after_safe_mode = false): string
    {
        return \str_replace(
            $after_safe_mode === true
                ? static::$latin_safe_analogs
                : static::$latin_analogs,
            static::$cyr_chars,
            $string
        );
    }
}
