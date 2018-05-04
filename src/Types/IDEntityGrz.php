<?php

namespace AvtoDev\IDEntity\Types;

use Exception;
use Illuminate\Support\Str;
use AvtoDev\IDEntity\Helpers\Transliterator;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegions;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegionEntry;

/**
 * Class IDEntityGrz.
 *
 * Идентификатор - номер ГРЗ.
 */
class IDEntityGrz extends AbstractTypedIDEntity implements HasRegionDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return static::ID_TYPE_GRZ;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value)
    {
        try {
            // Переводим в верхний регистр + trim
            $value = Str::upper(trim((string) $value));

            // Удаляем все символы, кроме разрешенных
            $value = preg_replace('~[^' . 'АВЕКМНОРСТУХ' . 'ABEKMHOPCTYX' . '0-9]~u', '', $value);

            // Производим замену латинских аналогов на кириллические (обратная транслитерация). Не прогоняю по всем
            // возможными символам, так как регулярка что выше всё кроме них как раз и удаляет
            $value = Transliterator::detransliterateLite($value);

            return $value;
        } catch (Exception $e) {
            // Do nothing
        }
    }

    /**
     * Возвращает код региона ГРЗ номера.
     *
     * @return int|null
     */
    public function getRegionCode()
    {
        preg_match('~(?<region_code>(7[1579]\d{1}|1\d{2}|\d{1,2}))$~D', $value = $this->getValue(), $matches);

        if (isset($matches['region_code']) && ! empty($region_code = $matches['region_code'])) {
            if (Str::length($region_code) === 3) {
                // В случае, если ГРЗ имеет вид 'АА77777' то проверяем - перед кодом региона всего 2 цифры? И если да -
                // то уменьшаем код региона на один символ
                if (preg_match("~\D\d{5}$~D", $value) === 1) {
                    $region_code = Str::substr($region_code, 1);
                } elseif (Str::startsWith($region_code, '10') && ! Str::endsWith($region_code, '2')) {
                    // Только '102' регион начинается с 10. В противном случае это говорит о том что '1' в начале лишняя
                    $region_code = Str::substr($region_code, 2);
                }
            }

            return (int) ltrim($region_code, '0');
        }
    }

    /**
     * Возвращает данные региона по коду региона ГРЗ.
     *
     * @return AutoRegionEntry|null
     */
    public function getRegionData()
    {
        /** @var AutoRegions $reference */
        $reference = resolve(AutoRegions::class);

        return $reference->getByAutoCode($this->getRegionCode());
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidateCallbacks()
    {
        return [
            function () {
                return $this->validateWithValidatorRule($this->getValue(), 'required|string|grz_code');
            },
            function () {
                // Регион существует
                return $this->getRegionData() instanceof AutoRegionEntry;
            },
        ];
    }
}
