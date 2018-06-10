<?php

namespace AvtoDev\IDEntity\Types;

use AvtoDev\StaticReferences\References\AutoRegions\AutoRegionEntry;

/**
 * Сущность, содержащая данные о регионе.
 */
interface HasRegionDataInterface
{
    /**
     * Возвращает код региона, связанный с идентификатором.
     *
     * @return int|null
     */
    public function getRegionCode();

    /**
     * Возвращает данные региона, связанного с идентификатором.
     *
     * @return AutoRegionEntry|null
     */
    public function getRegionData();
}
