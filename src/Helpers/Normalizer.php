<?php

namespace AvtoDev\IDEntity\Helpers;

/**
 * Class Normalizer.
 *
 * Статический строковый нормализатор.
 */
class Normalizer
{
    /**
     * Нормализует символы дефиса.
     *
     * @see: <https://ru.wikipedia.org/wiki/%D0%A2%D0%B8%D1%80%D0%B5>
     *
     * @param string $input
     *
     * @return string|null
     */
    public static function normalizeDashChar($input)
    {
        if (is_string($input) && ! empty($input)) {
            return str_replace([
                '–' /* Юникод U+2013 */,
                '—' /* Юникод U+2014 */,
                '‒' /* Юникод U+2012 */,
                '―' /* Юникод U+2015 */,
            ], '-', $input);
        }
    }
}
