<?php

namespace AvtoDev\IDEntity\Types;

use AvtoDev\StaticReferences\References\Entities\SubjectCodesInfo;

interface HasRegionDataInterface
{
    /**
     * Get subject code, which is associated with current identifier.
     *
     * @return int|null
     */
    public function getRegionCode(): ?int;

    /**
     * Get extended information about region, which is associated with current identifier.
     *
     * @return SubjectCodesInfo|null
     */
    public function getRegionData(): ?SubjectCodesInfo;
}
