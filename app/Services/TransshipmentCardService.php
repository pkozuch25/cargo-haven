<?php

namespace App\Services;

use Exception;
use App\Models\DispositionUnit;
use App\Models\TransshipmentCard;
use App\Enums\OperationRelationEnum;
use Illuminate\Support\Facades\Auth;
use App\Enums\TransshipmentCardStatusEnum;
use App\Models\StorageCell;
use App\Models\TransshipmentCardUnit;

class TransshipmentCardService
{
    public function getAvailableCards(DispositionUnit $operation) : array
    {
        $operation->loadMissing('disposition');
        if (!$operation->disposition) {
            return [];
        }

        $disposition = $operation->disposition;

        $availableCards = TransshipmentCard::query()
            ->where('tc_status', TransshipmentCardStatusEnum::PROCESSING)
            ->where('tc_relation_from', $disposition->dis_relation_from)
            ->where('tc_relation_to', $disposition->dis_relation_to)
            ->select('tc_number', 'tc_id')
            ->get()
            ->toArray();

        return $availableCards;
    }

    public function createNewCard(DispositionUnit $operation) : TransshipmentCard
    {
        $operation->loadMissing('disposition');
        $disposition = $operation->disposition;

        if (!$disposition) {
            throw new Exception(__("Cannot create new transshipment card - disposition not found."));
        }

        $newCard = new TransshipmentCard();
        $newCard->tc_relation_from = $disposition->dis_relation_from;
        $newCard->tc_relation_to = $disposition->dis_relation_to;
        $newCard->tc_created_by_user = Auth::id();
        $newCard->tc_yard_id = $disposition->dis_yard_id;
        $newCard->tc_status = TransshipmentCardStatusEnum::PROCESSING;
        $newcardWithNumber = $this->createTransshipmentCardNumber($newCard);

        $newCard->save();

        return $newcardWithNumber;
    }

    public function createTransshipmentCardNumber(TransshipmentCard $transshipmentCard): TransshipmentCard
    {
        $year = now()->year;
        $month = str_pad(now()->month, 2, '0', STR_PAD_LEFT);

        $transshipmentCard->loadMissing('storageYard');

        $yardShortName = strtoupper($transshipmentCard->storageYard?->sy_name_short);

        $lastTransshipmentCard = TransshipmentCard::where('tc_yard_id', $transshipmentCard->tc_yard_id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->latest('tc_month_number')
            ->first();

        $nextNumber = $lastTransshipmentCard ? $lastTransshipmentCard->tc_month_number + 1 : 1;
        $transshipmentCard->tc_month_number = $nextNumber;

        $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $relationFromLetter = $transshipmentCard->tc_relation_from->firstLetter();
        $relationToLetter = $transshipmentCard->tc_relation_to->firstLetter();

        $transshipmentCard->tc_number = "{$year}/{$month}/{$yardShortName}/{$formattedNumber}/{$relationFromLetter}/{$relationToLetter}";

        return $transshipmentCard;
    }

    public function calcGrossWeight(TransshipmentCardUnit $cardUnit): TransshipmentCardUnit
    {
        $cardUnit->tcu_gross_weight = $cardUnit->tcu_net_weight + $cardUnit->tcu_tare_weight;
        return $cardUnit;
    }

    public function createYardPosistion(TransshipmentCardUnit $cardUnit, StorageCell $cell): TransshipmentCardUnit
    {
        $cardUnit->tcu_yard_position = $cell->sc_yard_name_short . '/' . $cell->sc_cell. '/' . $cell->sc_row . '/' . $cell->sc_height;
        return $cardUnit;
    }
}