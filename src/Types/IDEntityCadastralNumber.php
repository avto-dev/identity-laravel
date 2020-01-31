<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use Exception;
use AvtoDev\IDEntity\Helpers\CadastralNumberInfo;
use AvtoDev\StaticReferences\References\CadastralDistricts;
use AvtoDev\StaticReferences\References\Entities\CadastralDistrict;
use AvtoDev\ExtendedLaravelValidator\Extensions\CadastralNumberValidatorExtension;

class IDEntityCadastralNumber extends AbstractTypedIDEntity implements HasCadastralNumberInterface
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
     * Return parsed fragments of cadastral number.
     *
     * @return CadastralNumberInfo
     */
    public function getNumberInfo(): CadastralNumberInfo
    {
        return CadastralNumberInfo::parse($this->value);
    }

    /**
     * {@inheritdoc}
     */
    public function getRegionData(): ?CadastralDistrict
    {
        /** @var CadastralDistricts $districts */
        $districts = static::getContainer()->make(CadastralDistricts::class);

        return $districts->getByCode((int) $this->getNumberInfo()->getRegionCode());
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        /** @var CadastralNumberValidatorExtension $validator */
        $validator = static::getContainer()->make(CadastralNumberValidatorExtension::class);

        $validated = \is_string($this->value) && $validator->passes('', $this->value);

        $region_data = $this->getRegionData();

        return $validated
               && $region_data instanceof CadastralDistrict
               && $region_data->hasAreaWithCode((int) $this->getNumberInfo()->getDistrictCode());
    }
}
