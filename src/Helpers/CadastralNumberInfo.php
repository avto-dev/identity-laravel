<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Helpers;

class CadastralNumberInfo
{
    /**
     * Код субъекта.
     *
     * @var int
     */
    protected $district_code;

    /**
     * Номер района.
     *
     * @var int
     */
    protected $area_code;

    /**
     * Номер квартала.
     *
     * @var string
     */
    protected $section_code;

    /**
     * Номер участка.
     *
     * @var string
     */
    protected $parcel_number;

    /**
     * CadastralNumberInfo constructor.
     *
     * @param int    $district_code
     * @param int    $area_code
     * @param string $section_code
     * @param string $parcel_number
     */
    protected function __construct(int $district_code,
                                   int $area_code,
                                   string $section_code,
                                   string $parcel_number)
    {
        $this->district_code = $district_code;
        $this->area_code     = $area_code;
        $this->section_code  = $section_code;
        $this->parcel_number = $parcel_number;
    }

    /**
     * Parse given cadastral number.
     *
     * @param string|null $cadastral_number
     *
     * @return CadastralNumberInfo
     */
    public static function parse(?string $cadastral_number)
    {
        $codes = \mb_split(':', $cadastral_number ?? '');

        return new self(
            (int) ($codes[0] ?? 0),
            (int) ($codes[1] ?? 0),
            \trim($codes[2] ?? ''),
            \trim($codes[3] ?? '')
        );
    }

    /**
     * @return int
     */
    public function getDistrictCode(): int
    {
        return $this->district_code;
    }

    /**
     * @return int
     */
    public function getAreaCode(): int
    {
        return $this->area_code;
    }

    /**
     * @return string
     */
    public function getSectionCode(): string
    {
        return $this->section_code;
    }

    /**
     * @return string
     */
    public function getParcelNumber(): string
    {
        return $this->parcel_number;
    }

    /**
     * Get all parsed elements in array.
     *
     * @return array<string, int|string>
     */
    public function getFragments(): array
    {
        return [
            'district'      => $this->district_code,
            'area'          => $this->area_code,
            'section'       => $this->section_code,
            'parcel_number' => $this->parcel_number,
        ];
    }
}
