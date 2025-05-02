<?php

namespace App\DataTypes;

class StorageCellsAvailabilityDataType
{
    public function __construct(
        public readonly bool $areAvailable,
        public readonly string $occupiedCellsText
    ) {}
}
