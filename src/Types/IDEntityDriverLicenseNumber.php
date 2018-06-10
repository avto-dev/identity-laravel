<?php

namespace AvtoDev\IDEntity\Types;

use Exception;
use Illuminate\Support\Str;
use AvtoDev\IDEntity\Helpers\Transliterator;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegions;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegionEntry;

/**
 * Идентификатор - номер водительского удостоверения.
 */
class IDEntityDriverLicenseNumber extends AbstractTypedIDEntity implements HasRegionDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return static::ID_TYPE_DRIVER_LICENSE_NUMBER;
    }

    /**
     * Возвращает код региона из номера водительского удостоверения.
     *
     * Первые четыре цифры номера — это серия документа. Две первые из них совпадают с номером региона, где ВУ было
     * выдано.
     *
     * @return int|null
     */
    public function getRegionCode()
    {
        \preg_match('~^(?<region_digits>[\d]{2}).+$~', $this->getValue(), $matches);

        if (isset($matches['region_digits']) && \is_numeric($region_digits = (string) $matches['region_digits'])) {
            return (int) $region_digits;
        }
    }

    /**
     * Возвращает данные региона из номера водительского удостоверения.
     *
     * @return AutoRegionEntry|null
     */
    public function getRegionData()
    {
        /** @var AutoRegions $auto_regions */
        $auto_regions = resolve(AutoRegions::class);

        return $auto_regions->getByRegionCode($this->getRegionCode());
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value)
    {
        try {
            // Переводим в верхний регистр + trim
            $value = Str::upper(trim((string) $value));

            // Удаляем все символы, кроме разрешенных (ВКЛЮЧАЯ разделители)
            $value = \preg_replace('~[^' . 'АВЕКМНОРСТУХ' . 'ABEKMHOPCTYX' . '0-9]~u', '', $value);

            // Производим замену латинских аналогов на кириллические (обратная транслитерация). Не прогоняю по всем
            // возможными символам, так как регулярка что выше всё кроме них как раз и удаляет
            $value = Transliterator::detransliterateLite($value);

            return $value;
        } catch (Exception $e) {
            // Do nothing
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidateCallbacks()
    {
        return [
            function () {
                return $this->validateWithValidatorRule($this->getValue(), 'required|string|driver_license_number');
            },
            function () {
                // Регион существует
                return $this->getRegionData() instanceof AutoRegionEntry;
            },
        ];
    }
}
