<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use Exception;
use Illuminate\Support\Str;
use AvtoDev\IDEntity\Helpers\Transliterator;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegions;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegionEntry;
use AvtoDev\ExtendedLaravelValidator\Extensions\DriverLicenseNumberValidatorExtension;

class IDEntityDriverLicenseNumber extends AbstractTypedIDEntity implements HasRegionDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
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
    public function getRegionCode(): ?int
    {
        \preg_match('~^(?<region_digits>[\d]{2}).+$~', (string) $this->getValue(), $matches);

        if (isset($matches['region_digits']) && \is_numeric($region_digits = (string) $matches['region_digits'])) {
            return (int) $region_digits;
        }

        return null;
    }

    /**
     * Возвращает данные региона из номера водительского удостоверения.
     *
     * @return AutoRegionEntry|null
     */
    public function getRegionData(): ?AutoRegionEntry
    {
        static $regions = null;

        if (! $regions instanceof AutoRegions) {
            $regions = new AutoRegions;
        }

        return $regions->getByRegionCode($this->getRegionCode());
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        try {
            // Переводим в верхний регистр + trim
            $value = Str::upper(\trim((string) $value));

            // Удаляем все символы, кроме разрешенных (ВКЛЮЧАЯ разделители)
            $value = (string) \preg_replace('~[^' . 'АВЕКМНОРСТУХ' . 'ABEKMHOPCTYX' . '0-9]~u', '', $value);

            // Производим замену латинских аналогов на кириллические (обратная транслитерация). Не прогоняю по всем
            // возможными символам, так как регулярка что выше всё кроме них как раз и удаляет
            $value = Transliterator::detransliterateLite($value);

            return $value;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        /** @var DriverLicenseNumberValidatorExtension $validator */
        $validator = static::getContainer()->make(DriverLicenseNumberValidatorExtension::class);

        return \is_string($this->value)
               && $validator->passes('', $this->value)
               && $this->getRegionData() instanceof AutoRegionEntry;
    }
}
