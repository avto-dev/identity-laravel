<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Helpers;

use AvtoDev\IDEntity\Helpers\Normalizer;
use AvtoDev\IDEntity\Tests\AbstractTestCase;

/**
 * @covers \AvtoDev\IDEntity\Helpers\Normalizer
 */
class NormalizerTest extends AbstractTestCase
{
    /**
     * Тест метода нормализации символа тире.
     *
     * @return void
     */
    public function testNormalizeDashChar(): void
    {
        foreach ([
                     '–' /* Юникод U+2013 */,
                     '—' /* Юникод U+2014 */,
                     '‒' /* Юникод U+2012 */,
                     '―' /* Юникод U+2015 */,
                     '-',
                 ] as $dash) {
            $this->assertEquals('-', Normalizer::normalizeDashChar($dash));
        }

        $this->assertSame('', Normalizer::normalizeDashChar(''));
    }
}
