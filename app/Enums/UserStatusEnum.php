<?php

namespace App\Enums;

enum UserStatusEnum: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;
    case REJECTED = 2;

    public function name(): string
    {
        return match ($this) {
            $this::INACTIVE => __('Inactive'),
            $this::ACTIVE => __('Active'),
            $this::REJECTED => __('Rejected'),
        };
    }
}
