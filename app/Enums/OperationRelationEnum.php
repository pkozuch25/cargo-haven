<?php

namespace App\Enums;

use App\Traits\ExcludeEnumCasesTrait;

enum OperationRelationEnum: int
{
    use ExcludeEnumCasesTrait;
    
    case CARRIAGE = 0;
    case YARD = 1;
    case TRUCK = 2;

    public function name(): string
    {
        return match ($this) {
            $this::CARRIAGE => __('Carriage'),
            $this::YARD => __('Yard'),
            $this::TRUCK => __('Truck')
        };
    }

}
