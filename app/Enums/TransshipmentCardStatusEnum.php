<?php

namespace App\Enums;

enum TransshipmentCardStatusenum: int
{
    case PROCESSING = 0;
    case COMPLETED = 1;

    public function name(): string
    {
        return match ($this) {
            $this::PROCESSING => __('Processing'),
            $this::COMPLETED => __('Completed')
        };
    }

    public function color(): string
    {
        return match ($this) {
            $this::PROCESSING => 'bg-secondary',
            $this::COMPLETED => 'bg-success'
        };
    }
}
