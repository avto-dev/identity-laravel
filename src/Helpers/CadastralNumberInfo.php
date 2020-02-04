<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Helpers;

/**
 * @link <https://bit.ly/37TO76H>
 */
class CadastralNumberInfo implements \Illuminate\Contracts\Support\Arrayable
{
    /**
     * @var int
     */
    protected $district_code;

    /**
     * @var int
     */
    protected $area_code;

    /**
     * @var int
     */
    protected $section_code;

    /**
     * @var int
     */
    protected $parcel_number;

    /**
     * Create a new CadastralNumberInfo instance.
     *
     * @param int $district_code
     * @param int $area_code
     * @param int $section_code
     * @param int $parcel_number
     */
    protected function __construct(int $district_code,
                                   int $area_code,
                                   int $section_code,
                                   int $parcel_number)
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
            (int) ($codes[2] ?? 0),
            (int) ($codes[3] ?? 0)
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
     * @return int
     */
    public function getSectionCode(): int
    {
        return $this->section_code;
    }

    /**
     * @return int
     */
    public function getParcelNumber(): int
    {
        return $this->parcel_number;
    }

    /**
     * @return array{district:int, area:int, section:int, parcel_number:int}
     */
    public function toArray(): array
    {
        return [
            'district'      => $this->district_code,
            'area'          => $this->area_code,
            'section'       => $this->section_code,
            'parcel_number' => $this->parcel_number,
        ];
    }
}
