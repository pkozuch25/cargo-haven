<?php

namespace App\Services;

use App\Enums\DispositionStatusEnum;
use App\Models\Disposition;

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
}
