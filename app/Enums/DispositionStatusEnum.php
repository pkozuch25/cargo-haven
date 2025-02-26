<?php

namespace App\Enums;

enum DispositionStatusEnum: int
{
    case NOT_CONFIRMED = 0;
    case CONFIRMED = 1;
    case PROCESSING = 2;
    case FINALIZED = 3;
    case CANCELLED = 4;

    public function name(): string
    {
        return match ($this) {
            $this::NOT_CONFIRMED => __('Not confirmed'),
            $this::CONFIRMED => __('Confirmed'),
            $this::PROCESSING => __('Processing'),
            $this::FINALIZED => __('Finalized'),
            $this::CANCELLED => __('Cancelled'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            $this::NOT_CONFIRMED => 'bg-secondary',
            $this::CONFIRMED => 'bg-success',
            $this::PROCESSING => 'bg-teal-600',
            $this::FINALIZED => 'bg-sky-600',
            $this::CANCELLED => 'bg-danger',
        };
    }
}
