<?php

namespace AvtoDev\IDEntity\Types;

use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralRegionEntry;

interface HasCadastralNumberInterface
{
    /**
     * Return cadastral data by region & district codes.
     *
     * @return CadastralRegionEntry|null
     */
    public function getRegionData(): ?CadastralRegionEntry;
}
