<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use Illuminate\Support\Str;
use AvtoDev\IDEntity\IDEntityInterface;
use AvtoDev\IDEntity\Types\IDEntityEpts;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(IDEntityEpts::class)]
class IDEntityEptsTest extends AbstractIDEntityTestCase
{
    /**
     * @var string
     */
    protected $expected_type = IDEntityInterface::ID_TYPE_EPTS;

    /**
     * {@inheritdoc}
     */
    public function testNormalize(): void
    {
        $entity = $this->entityFactory();

        $this->assertSame('123456789012345', $entity::normalize('  123-456.789_012 345  ')); // Базовая стандартная коррекция - весь комплекс
        $this->assertSame('123456789012345', $entity::normalize('123/456_789-012.345'));     // Удаление разделителей
        $this->assertSame('123456789012345', $entity::normalize('123 456 789 012 345'));     // Удаление пробелов
        $this->assertSame('123456789012345', $entity::normalize('123A456B789C012D345'));     // Удаление букв
        $this->assertSame('123456789012345', $entity::normalize('123А456Б789В012Г345'));     // Удаление кириллических букв
    }

    /**
     * {@inheritdoc}
     */
    protected function entityFactory(?string $value = null): IDEntityEpts
    {
        return new IDEntityEpts($value ?? $this->getValidValues()[0]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValues(): array
    {
        return [
            '123451789012345', // просто валидный EPTS, состоящий из 15 цифр
            '100001100000000', // валидный EPTS начинающийся с 1
            '200001100000000', // валидный EPTS начинающийся с 2
            '300001100000000', // валидный EPTS начинающийся с 3
            '100001100000000', // валидный EPTS с шестым символом 1
            '100002100000000', // валидный EPTS с шестым символом 2
            '100003100000000', // валидный EPTS с шестым символом 3
            '100004100000000', // валидный EPTS с шестым символом 4
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getInvalidValues(): array
    {
        return [
            '12345178901234',      // менее 15 символов
            '1234517890123456',    // более 15 символов
            '12345178901234A',     // содержит латинскую букву
            '12345178901234А',     // содержит кириллическую букву
            '12345178901234.',     // содержит точку
            '123451789-12345',     // содержит дефис
            '123451789 12345',     // содержит пробел
            '000001100000000',     // не может начинаться с 0
            '400001100000000',     // не может начинаться с 4
            '500001100000000',     // не может начинаться с 5
            '600001100000000',     // не может начинаться с 6
            '700001100000000',     // не может начинаться с 7
            '800001100000000',     // не может начинаться с 8
            '900001100000000',     // не может начинаться с 9
            '100000100000000',     // шестой символ не может быть 0
            '100005100000000',     // шестой символ не может быть 5
            '100006100000000',     // шестой символ не может быть 6
            '100007100000000',     // шестой символ не может быть 7
            '100008100000000',     // шестой символ не может быть 8
            '100009100000000',     // шестой символ не может быть 9

            // Другие невалидные значения
            'TSMEYB21S00610448',
            'LN130-0128818',
            '38:49:924785:832907',
            'A111AA177',
            'А123АА77',
            '78УЕ952328',
            '7887952328',
            '',
            Str::random(32),
        ];
    }
}
