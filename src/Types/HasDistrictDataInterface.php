<?php

namespace AvtoDev\IDEntity\Types;

use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralDistrictEntry;

interface HasDistrictDataInterface
{
    /**
     * Return cadastral data by region & district codes.
     *
     * @return CadastralDistrictEntry|null
     */
    public function getDistrictData(): ?CadastralDistrictEntry;
}
