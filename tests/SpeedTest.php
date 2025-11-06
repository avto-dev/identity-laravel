<?php
declare(strict_types=1);

namespace AvtoDev\IDEntity\Tests;

use AvtoDev\IDEntity\Types\IDEntityBody;

/**
 * @coversNothing
 */
class SpeedTest extends AbstractTestCase
{
    protected ?int $cyr_chars_count = 30;

    protected ?array $cyr_chars_lower = ['а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
        'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'э', 'ю', 'я'];

    /**
     * Метод, который позволяет замерить время работы.
     * @return void
     */
    public function testSpeed(): void
    {
        $filename = '';
        //$filename = __DIR__ . '/../tmp/400k-body.txt';
        $is_valid = $not_valid = 0;

        if ($filename === '') {
            $values = [];
            for ($i = 0; $i < 0; $i++) {
                $values[] = $this->makeCyrString(15);
            }
        } else {
            $values = array_map(function ($value) {
                return \trim($value);
            }, $this->getFromFile($filename));
        }

        $start = microtime(true);

        foreach ($values as $value) {
            $norm = IDEntityBody::normalize($value);

            if ($norm === null) {
                $not_valid++;
            } else {
                $is_valid++;
            }
        }

        $end = microtime(true);

        $time_elapsed = $end - $start;

//        echo sprintf("Время выполнения: %.6f сек.\n", $time_elapsed);
//        echo sprintf("Невалидных: %d шт.\n", $not_valid);
//        echo sprintf("Валидных: %d шт.\n", $is_valid);
        $this->assertTrue(true);
    }

    /**
     * Создает строку из кириллических символов.
     *
     * @param int $length
     * @return string
     */
    protected function makeCyrString(int $length): string
    {
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $this->cyr_chars_lower[mt_rand(0, $this->cyr_chars_count - 1)];
        }

        return $string;
    }

    /**
     * Читает файл и возвращает массив строк.
     *
     * @param $filename
     * @return array<int, string>
     */
    protected function getFromFile($filename): array
    {
        $result = file($filename);

        if ($result === false) {
            throw new \RuntimeException('File not found');
        }

        return file($filename);
    }
}
