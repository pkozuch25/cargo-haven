<?php

namespace App\Enums;

enum DispositionUnitStatusEnum: int
{
    case OK = 0;
    case CANCELLED = 1;

    public function name(): string
    {
        return match ($this) {
            $this::OK => __('OK'),
            $this::CANCELLED => __('Cancelled'),
        };
    }

}
