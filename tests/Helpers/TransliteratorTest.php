<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Helpers;

use AvtoDev\IDEntity\Helpers\Transliterator;
use AvtoDev\IDEntity\Tests\AbstractTestCase;

/**
 * @covers \AvtoDev\IDEntity\Helpers\Transliterator
 */
class TransliteratorTest extends AbstractTestCase
{
    /**
     * Тест метода транслитерации.
     *
     * @return void
     */
    public function testTransliterateString(): void
    {
        $asserts = [
            'привет'    => 'privet',
            'ПРИВЕТ'    => 'PRIVET',
            'А123aa177' => 'A123aa177',
        ];

        foreach ($asserts as $what => $with) {
            $this->assertSame($with, Transliterator::transliterateString($what, false));
        }

        $this->assertSame(
            'a b v g d e e zh z i y k l m n o p r s t u f kh ts ch sh shch  y  e yu ya',
            Transliterator::transliterateString(
                'а б в г д е ё ж з и й к л м н о п р с т у ф х ц ч ш щ ъ ы ь э ю я',
                false
            )
        );

        // И теперь safe-режим

        $asserts_safe = [
            'привет'    => 'ppibet',
            'ПРИВЕТ'    => 'PPIBET',
            'А123aa177' => 'A123aa177',
        ];

        foreach ($asserts_safe as $what => $with) {
            $this->assertSame($with, Transliterator::transliterateString($what, true));
        }

        $this->assertSame(
            'a b b g d e e j z i i k l m h o p p c t y f x c c w w    e u y',
            Transliterator::transliterateString(
                'а б в г д е ё ж з и й к л м н о п р с т у ф х ц ч ш щ ъ ы ь э ю я',
                true
            )
        );
    }

    /**
     * Тест метода обратной транслитерации.
     *
     * @return void
     */
    public function testDetransliterateString(): void
    {
        $asserts = [
            'privet'    => 'привет',
            'PRIVET'    => 'ПРИВЕТ',
            'A123aa177' => 'А123аа177',
        ];

        foreach ($asserts as $what => $with) {
            $this->assertSame($with, Transliterator::detransliterateString($what));
        }

        $this->assertSame(
            'а б в г д е е ж з и ы к л м н о п р с т у ф кх тс ч сх схч ы е ыу ыа',
            Transliterator::detransliterateString(
                'a b v g d e e zh z i y k l m n o p r s t u f kh ts ch sh shch y e yu ya'
            )
        );
    }

    public function testLiteLtansliterator(): void
    {
        $this->assertSame(
            'АВЕКМНОРСТУХ авекмнорстух',
            Transliterator::detransliterateLite('ABEKMHOPCTYX abekmhopctyx')
        );
        $this->assertSame(
            'ABEKMHOPCTYX abekmhopctyx',
            Transliterator::transliterateLite('АВЕКМНОРСТУХ авекмнорстух')
        );
    }
}
