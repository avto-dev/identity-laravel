<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use AvtoDev\IDEntity\Helpers\Transliterator;
use AvtoDev\StaticReferences\References\SubjectCodes;
use AvtoDev\StaticReferences\References\Entities\SubjectCodesInfo;
use AvtoDev\ExtendedLaravelValidator\Extensions\DriverLicenseNumberValidatorExtension;

class IDEntityDriverLicenseNumber extends AbstractTypedIDEntity implements HasRegionDataInterface
{
    /**
     * {@inheritdoc}
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
        return static::ID_TYPE_DRIVER_LICENSE_NUMBER;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        try {
            // Uppercase + trim
            $value = \mb_strtoupper(\trim((string) $value), 'UTF-8');

            // Remove all chars except allowed (delimiters are included)
            $value = (string) \preg_replace('~[^' . 'АВЕКМНОРСТУХ' . 'ABEKMHOPCTYX' . '0-9]~u', '', $value);

            // Transliterate latin- chars with kyr- (backward transliteration)
            $value = Transliterator::detransliterateLite($value);

            return $value;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Get region code from value.
     *
     * First 4 digits in value is serial number. First two is region code where driver license was issued.
     *
     * @return int|null
     */
    public function getRegionCode(): ?int
    {
        if (\is_string($this->value)) {
            \preg_match('~^(?<region_digits>[\d]{2}).+$~', $this->value, $matches);

            $region_digits = $matches['region_digits'] ?? null;

            if (\is_numeric($region_digits)) {
                return (int) $region_digits;
            }
        }

        return null;
    }

    /**
     * Get information about region where driver license was issued.
     *
     * @see \AvtoDev\StaticReferences\ServiceProvider Must be loaded
     *
     * @return SubjectCodesInfo|null
     */
    public function getRegionData(): ?SubjectCodesInfo
    {
        $region_code = $this->getRegionCode();

        if (\is_int($region_code)) {
            /** @var SubjectCodes $subjects */
            $subjects = static::getContainer()->make(SubjectCodes::class);

            return $subjects->getBySubjectCode($region_code);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        if (\is_string($this->value) && $this->value !== '') {
            /** @var DriverLicenseNumberValidatorExtension $validator */
            $validator = static::getContainer()->make(DriverLicenseNumberValidatorExtension::class);

            return $validator->passes('', $this->value) && $this->getRegionData() instanceof SubjectCodesInfo;
        }

        return false;
    }
}
