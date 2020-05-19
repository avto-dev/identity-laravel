<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Helpers;

/**
 * @internal
 */
class Normalizer
{
    /**
     * Normalize "dash" symbol.
     *
     * @see: <https://ru.wikipedia.org/wiki/%D0%A2%D0%B8%D1%80%D0%B5>
     *
     * @param string $input
     *
     * @return string
     */
    public static function normalizeDashChar(string $input): string
    {
        return \str_replace([
            '–' /* Unicode U+2013 */,
            '—' /* Unicode U+2014 */,
            '‒' /* Unicode U+2012 */,
            '―' /* Unicode U+2015 */,
        ], '-', $input);
    }
}
