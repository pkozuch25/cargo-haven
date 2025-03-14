<?php

namespace App\Services;

use App\Enums\DispositionStatusEnum;
use App\Models\Deposit;
use App\Models\Disposition;
use App\Models\DispositionUnit;

class DispositionService
{
    public function checkIfOperatorBelongsToDisposition(array $data, array $dataBefore)
    {
        $operatorId = array_diff($data, $dataBefore);
        if (count($data) > count($dataBefore) && count($operatorId) > 0) {
            $dispositions = Disposition::with('operators')
                ->whereIn('dis_status', DispositionStatusEnum::active())
                ->whereRelation('operators', 'disope_user_id', $operatorId)
                ->get()
                ->pluck('dis_number')
                ->toArray();

            if ($dispositions != []) {
                $dispositionsString = implode(', ', $dispositions);
                return $dispositionsString;
            }
            return null;
        }
    }

    public function createDispositionNumber(Disposition $disposition) : Disposition
    {
        $year = now()->year;
        $month = str_pad(now()->month, 2, '0', STR_PAD_LEFT);

        $disposition->loadMissing('storageYard');

        $yardShortName = strtoupper($disposition->storageYard?->sy_name_short);

        $lastDisposition = Disposition::where('dis_yard_id', $disposition->dis_yard_id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->latest('dis_month_number')
            ->first();

        $nextNumber = $lastDisposition ? $lastDisposition->dis_month_number + 1 : 1;
        $disposition->dis_month_number = $nextNumber;

        $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $disposition->dis_number = "{$year}/{$month}/{$yardShortName}/{$formattedNumber}";

        return $disposition;
    }

    public function checkIfDispositionHasAnyUnits(Disposition $disposition) : bool
    {
        $disposition->loadMissing('units');
        return $disposition->units()->exists();
    }

    public function calcGrossWeight(DispositionUnit $dispositionUnit): DispositionUnit
    {
        $dispositionUnit->disu_container_gross_weight = $dispositionUnit->disu_container_net_weight + $dispositionUnit->disu_container_tare_weight;
        return $dispositionUnit;
    }

    public function checkIfUnitExistsInDisposition($containerNumber) : Disposition | null
    {
        $dispositionWithContainerNumber = DispositionUnit::with('disposition')->where('disu_container_number', $containerNumber)->first();
        return $dispositionWithContainerNumber ? $dispositionWithContainerNumber->disposition : null;
    }

    public function checkIfUnitExistsInDeposits($containerNumber) : bool
    {
        $depositWithContainerNumber = Deposit::available()->where('dep_number', $containerNumber)->first();
        return $depositWithContainerNumber ? true : false;
    }
}
