<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralDistrictEntry;
use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralRegionEntry;
use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralRegions;
use Exception;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegions;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegionEntry;
use AvtoDev\ExtendedLaravelValidator\Extensions\CadastralNumberValidatorExtension;

class IDEntityCadastralNumber extends AbstractTypedIDEntity implements HasDistrictDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return static::ID_TYPE_CADASTRAL_NUMBER;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        try {
            // Удаляем все символы, кроме разрешенных (цифры и знак ":")
            return (string) \preg_replace('~[^\d\:]~u', '', (string) $value);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRegionCode(): ?int
    {
        \preg_match('~^(?<region>[0-9]{2})~', (string) $this->value, $matches);

        return isset($matches['region'])
            ? (int) $matches['region']
            : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getDistrictCode(): ?int
    {
        \preg_match('~^[0-9]{2}:(?<district>[0-9]{2})~', (string) $this->value, $matches);

        return isset($matches['district'])
            ? (int) $matches['district']
            : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getDistrictData(): ?CadastralDistrictEntry
    {
        static $districts = null;

        if (! $districts instanceof CadastralRegions) {
            $districts = new CadastralRegions;
        }
        $region   = $this->getRegionCode();
        $district = $this->getDistrictCode();

        if (($region = $districts->getRegionByCode($region)) instanceof CadastralRegionEntry) {
            return $region->getDistricts()->getDistrictByCode($district);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        /** @var CadastralNumberValidatorExtension $validator */
        $validator = static::getContainer()->make(CadastralNumberValidatorExtension::class);

        $validated = \is_string($this->value) && $validator->passes('', $this->value);

        return $validated && $this->getDistrictData() instanceof CadastralDistrictEntry;
    }
}
