<?php

namespace AvtoDev\IDEntity\Types;

use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralDistrictEntry;

interface HasDistrictDataInterface
{
    /**
     * Return cadastral region from identifier.
     *
     * @return int|string|mixed
     */
    public function getRegionCode();

    /**
     * Return cadastral district from identifier.
     *
     * @return int|string|mixed
     */
    public function getDistrictCode();

    /**
     * Return cadastral data by region & district codes.
     *
     * @return CadastralDistrictEntry|null
     */
    public function getDistrictData(): ?CadastralDistrictEntry;
}
