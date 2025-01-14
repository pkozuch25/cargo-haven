<?php

namespace App\Enums;

enum RegistrationRequestStatusEnum: int
{
    case PENDING = 0;
    case APPROVED = 1;
    case REJECTED = 2;

    public function name(): string
    {
        return match ($this) {
            $this::PENDING => __('Pending'),
            $this::APPROVED => __('Approved'),
            $this::REJECTED => __('Rejected')
        };
    }
}
