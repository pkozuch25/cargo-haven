<?php

namespace App\Traits;

trait ExcludeEnumCasesTrait
{
    public static function casesExcept(self|array $except): array
    {
        $except = is_array($except) ? $except : [$except];
        return array_filter(self::cases(), fn($case) => !in_array($case, $except));
    }
}