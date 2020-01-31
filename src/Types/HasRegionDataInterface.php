<?php

namespace AvtoDev\IDEntity\Types;

use AvtoDev\StaticReferences\References\Entities\SubjectCodesInfo;

interface HasRegionDataInterface
{
    /**
     * Возвращает код региона, связанный с идентификатором.
     *
     * @return int|null
     */
    public function getRegionCode(): ?int;

    /**
     * Возвращает данные региона, связанного с идентификатором.
     *
     * @return SubjectCodesInfo|null
     */
    public function getRegionData(): ?SubjectCodesInfo;
}
