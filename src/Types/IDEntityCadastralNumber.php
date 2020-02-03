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
     * {@inheritDoc}
     *
     * @return static
     */
    final public static function make(string $value, ?string $type = null): self
    {
        return new static($value);
    }

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
    public function getDistrictData(): ?CadastralDistrict
    {
        /** @var CadastralDistricts $districts */
        $districts = static::getContainer()->make(CadastralDistricts::class);

        return $districts->getByCode($this->getNumberInfo()->getDistrictCode());
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        /** @var CadastralNumberValidatorExtension $validator */
        $validator = static::getContainer()->make(CadastralNumberValidatorExtension::class);

        $validated = \is_string($this->value) && $validator->passes('', $this->value);

        $district_data = $this->getDistrictData();

        return $validated
               && $district_data instanceof CadastralDistrict
               && $district_data->hasAreaWithCode($this->getNumberInfo()->getAreaCode());
    }
}
