<?php

namespace App\Services;

use App\Enums\DispositionStatusEnum;
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

    public function createDispositionNumber()
    {
        //todo tworzy numer dyspozycji składający się z roku, miesiąca, placu składowego(skrótu) i 4 cyfrowego numeru porządkowego lp - np 2025/03/RZ/0001
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
}
