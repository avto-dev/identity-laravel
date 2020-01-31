<?php

namespace AvtoDev\IDEntity\Types;

use AvtoDev\StaticReferences\References\Entities\CadastralDistrict;

interface HasCadastralNumberInterface
{
    /**
     * Return cadastral data by region & district codes.
     *
     * @return CadastralDistrict|null
     */
    public function getDistrictData(): ?CadastralDistrict;
}
