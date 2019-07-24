<?php

namespace AvtoDev\IDEntity\Types;

use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralRegionEntry;

interface HasCadastralNumberInterface
{
    /**
     * Return cadastral data by region & district codes.
     *
     * @param string $region_code
     *
     * @return CadastralRegionEntry|null
     */
    public function getRegionData(string $region_code): ?CadastralRegionEntry;
}
