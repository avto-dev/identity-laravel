<?php

namespace AvtoDev\IDEntity\Types;

use AvtoDev\StaticReferences\References\Entities\CadastralDistrict;

interface HasCadastralNumberInterface
{
    /**
     * Get cadastral data by district & area codes.
     *
     * @return CadastralDistrict|null
     */
    public function getDistrictData(): ?CadastralDistrict;
}
