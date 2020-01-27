<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Helpers;

class CadastralNumberInfo
{
    /**
     * Код субъекта.
     *
     * @var string
     */
    protected $region_code;

    /**
     * Номер района.
     *
     * @var string
     */
    protected $district_code;

    /**
     * Номер квартала.
     *
     * @var string
     */
    protected $quarter_code;

    /**
     * Номер участка.
     *
     * @var string
     */
    protected $area_code;

    /**
     * CadastralNumberInfo constructor.
     *
     * @param string $region_code
     * @param string $district_code
     * @param string $quarter_code
     * @param string $area_code
     */
    protected function __construct(string $region_code, string $district_code, string $quarter_code, string $area_code)
    {
        $this->region_code   = $region_code;
        $this->district_code = $district_code;
        $this->quarter_code  = $quarter_code;
        $this->area_code     = $area_code;
    }

    /**
     * Parse given cadastral number.
     *
     * @param null|string $cadastral_number
     *
     * @return CadastralNumberInfo
     */
    public static function parse(?string $cadastral_number)
    {
        $codes = \mb_split(':', $cadastral_number ?? '');

        return new static(
            \trim($codes[0] ?? ''),
            \trim($codes[1] ?? ''),
            \trim($codes[2] ?? ''),
            \trim($codes[3] ?? '')
        );
    }

    /**
     * @return string
     */
    public function getRegionCode(): string
    {
        return $this->region_code;
    }

    /**
     * @return string
     */
    public function getDistrictCode(): string
    {
        return $this->district_code;
    }

    /**
     * @return string
     */
    public function getQuarterCode(): string
    {
        return $this->quarter_code;
    }

    /**
     * @return string
     */
    public function getAreaCode(): string
    {
        return $this->area_code;
    }

    /**
     * Get all parsed elements in array.
     *
     * @return array
     */
    public function getFragments(): array
    {
        return [
            'region'   => $this->region_code,
            'district' => $this->district_code,
            'quarter'  => $this->quarter_code,
            'area'     => $this->area_code,
        ];
    }
}
