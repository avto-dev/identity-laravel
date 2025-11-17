<?php
declare(strict_types=1);

namespace AvtoDev\IDEntity\Tests\Helpers;

use AvtoDev\IDEntity\Helpers\Strings;
use AvtoDev\IDEntity\Tests\AbstractTestCase;

/**
 * @covers \AvtoDev\IDEntity\Helpers\Strings
 */
class StringsTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testRemoveNonAlphanumericChars(): void
    {
        $cases = [
            '' => '',
            ' /' => '',
            '  \\' => '',
            'foo' => 'foo',
            ' bar ' => 'bar',
            'foo`!-~%^&&*()_+-*/-bar' => 'foobar',
            'fo1ob-ar' => 'fo1obar',
            'FO1OB-AR' => 'FO1OBAR',
            $rus = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ' => $rus,
            $eng = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' => $eng,
            $other = 'ABCDEFGHIJKLMNOPQRSTUVWXYZÄÖÜabcdefghijklmnopqrstuvwxyzäöüß' => $other,
            'A B C D E F G H I J K L M N O P Q R S T U V W X Y Z' => $eng,
        ];

        foreach ($cases as $from => $to) {
            $this->assertSame($to, Strings::removeNonAlphanumericChars($from),
                'Case "' . $from . '" failed'
            );
        }
    }

    /**
     * @return void
     */
    public function testReplaceByMap(): void
    {
        $cases = [
            [
                'val' => 'foobar',
                'map' => ['foo' => 'bar'],
                'expected' => 'barbar',
            ],
            [
                'val' => 'foobar',
                'map' => ['bar' => 'foo'],
                'expected' => 'foofoo',
            ],
            [
                'val' => 'foobar',
                'map' => ['bar' => 'foo', 'foofoo' => 154, 9 => 2],
                'expected' => '154',
            ],
            [
                'val' => 'foobar',
                'map' => ['f' => '1', 'o' => 2, 'r' => 'r', 'R' => 999],
                'expected' => '122bar',
            ],
            [
                'val' => '111222',
                'map' => [1 => 3, 2 => 4],
                'expected' => '333444',
            ],
        ];

        foreach ($cases as $num => $case) {
            $this->assertSame(
                $case['expected'], Strings::replaceByMap($case['val'], $case['map']),
                'Case #' . $num
            );
        }
    }

    /**
     * @return void
     */
    public function testReplaceByMapError(): void
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Object of class stdClass could not be converted to string');

        Strings::replaceByMap('foo', ['something' => new \stdClass()]);
    }

    /**
     * @return void
     */
    public function testHasSpecificCyrillicUpperLetters(): void
    {
        $specific = [
            'Ё', 'Б', 'Г', 'Д', 'Ж', 'З', 'И', 'Й', 'Л', 'П', 'Ф', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
        ];

        foreach ($specific as $letter) {
            $this->assertTrue(
                Strings::hasSpecificCyrillicUpperLetters($letter),
                'Case "' . $letter . '" failed'
            );
            $this->assertFalse(
                Strings::hasSpecificCyrillicUpperLetters(\mb_strtolower($letter, 'UTF-8')),
                'Case upper "' . $letter . '" failed'
            );
        }

        $other = [
            'А', 'В', 'Е', 'К', 'М', 'Н', 'О', 'Р', 'С', 'Т', 'У', 'Х', // Остальная кириллица
            'J', 'Q', 'L', 'R', // Латиница
            '1', '213', '4433', '55', // цифры
            '"""', '////', '&&', '&&', //
        ];

        foreach ($other as $letter) {
            $this->assertFalse(
                Strings::hasSpecificCyrillicUpperLetters($letter),
                'Case "' . $letter . '" failed'
            );
            $this->assertFalse(
                Strings::hasSpecificCyrillicUpperLetters(\mb_strtolower($letter, 'UTF-8')),
                'Case "' . $letter . '" failed'
            );
        }
    }
}
