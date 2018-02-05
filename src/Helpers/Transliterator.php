<?php

namespace AvtoDev\IDEntity\Helpers;

use Illuminate\Support\Str;

/**
 * Class Transliterator.
 *
 * Статический транслитератор / де-транслитератор.
 */
class Transliterator
{
    /**
     * Кириллические символы, для которых описан массив "мягких" замен $this->latin_chars.
     *
     * @var string[]
     */
    protected static $cyr_chars = [
        'А', 'В', 'Б', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'К', 'Л', 'М', 'Н', 'О', 'Р', 'С', 'Т', 'У', 'Ф',
        'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'И', 'Е', 'Ю', 'Я',

        'а', 'в', 'б', 'г', 'д', 'е', 'ж', 'з', 'и', 'к', 'л', 'м', 'н', 'о', 'р', 'с', 'т', 'у', 'ф',
        'х', 'ц', 'ч', 'ш', 'щ', 'и', 'е', 'ю', 'я',
    ];

    /**
     * Латинские аналоги кириллических символов, что описаны в массиве $this->cyr_chars.
     *
     * @var string[]
     */
    protected static $latin_chars = [
        'A', 'B', 'B', 'G', 'D', 'E', 'J', 'Z', 'I', 'K', 'L', 'M', 'H', 'O', 'P', 'C', 'T', 'Y', 'F',
        'X', 'C', 'H', 'W', 'W', 'I', 'E', 'U', 'Y',

        'a', 'b', 'b', 'g', 'd', 'e', 'j', 'z', 'i', 'k', 'l', 'm', 'h', 'o', 'p', 'c', 't', 'y', 'f',
        'x', 'c', 'h', 'w', 'w', 'i', 'e', 'u', 'y',
    ];

    /**
     * Переводит строку в верхний регистр и производит "безопасную" транслитерацию (без опаски что одна буква будет
     * транслитерирована как две).
     *
     * @param string $string
     *
     * @return string
     */
    public static function uppercaseAndSafeTransliterate($string)
    {
        // Производим замену латинских символов, которые при дальнейшей транслитерации дают 2 символа на выходе,
        // вместо одного (например 'я' -> 'ya'), и переводим в верхний регистр
        $string = str_replace(
            static::$cyr_chars,
            static::$latin_chars,
            Str::upper((string) $string)
        );

        // Производим конечную транслитерацию
        return Str::ascii($string);
    }

    /**
     * Переводит строку в верхний регистр и производит ОБРАТНУЮ "безопасную" транслитерацию.
     *
     * @param string $string
     *
     * @return string
     */
    public static function uppercaseAndSafeDeTransliterate($string)
    {
        // Производим замену кириллических символов на латинские аналоги, и переводим в верхний регистр
        return str_replace(
            static::$latin_chars,
            static::$cyr_chars,
            Str::upper((string) $string)
        );
    }
}
