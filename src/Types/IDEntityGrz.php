<?php

namespace AvtoDev\IDEntity\Types;

use AvtoDev\StaticReferencesLaravel\StaticReferences;
use Exception;
use Illuminate\Support\Str;
use AvtoDev\IDEntity\Helpers\Transliterator;
use AvtoDev\StaticReferencesLaravel\References\AutoRegions\AutoRegionEntry;

/**
 * Class IDEntityGrz.
 *
 * Идентификатор - номер ГРЗ.
 */
class IDEntityGrz extends AbstractTypedIDEntity
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
            $value = Transliterator::detransliterateString(Str::upper($value), true);

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
        // Могут быть: АА0001177, АА000177, Т900ММ77, В164ОЕ190
        preg_match('~[^\d](?<region_digits>\d+)$~', $this->getValue(), $bitchez);

        if (isset($bitchez['region_digits']) && is_numeric($region_digits = (string) $bitchez['region_digits'])) {
            $region_digits_length = strlen($region_digits);

            // Если код региона - 2 или 3 цифры - то сразу его возвращаем
            if ($region_digits_length >= 2 && $region_digits_length <= 3) {
                return (int) $region_digits;
            } else {
                // В противном случае - отбрасываем первые 4 символа
                $region_digits = substr($region_digits, 4);

                $region_digits_length = strlen($region_digits);
                // И производим простейшую валидацию
                if ($region_digits_length >= 2 && $region_digits_length <= 3) {
                    return (int) $region_digits;
                }
            }
        }

        return null;
    }

    /**
     * Возвращает данные региона по коду региона ГРЗ.
     *
     * @return AutoRegionEntry|null
     */
    public function getRegionData()
    {
        /** @var StaticReferences $static_references */
        $static_references = app()->make(StaticReferences::class);

        return $static_references->autoRegions->getByAutoCode($this->getRegionCode());
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
            }
        ];
    }
}
