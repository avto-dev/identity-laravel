<?php

namespace AvtoDev\IDEntity\Types;

use AvtoDev\StaticReferences\References\AutoRegions\AutoRegionEntry;

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
     * @return AutoRegionEntry|null
     */
    public function getRegionData(): ?AutoRegionEntry;
}
