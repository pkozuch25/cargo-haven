<?php

namespace App\Livewire\TransshipmentCards;

use Livewire\Attributes\On;
use App\Livewire\TableComponent;
use App\Models\TransshipmentCard;
use Livewire\Attributes\Computed;
use App\Enums\OperationRelationEnum;
use App\Enums\TransshipmentCardStatusenum;
use App\Interfaces\TableComponentInterface;

class TransshipmentCardsTable extends TableComponent implements TableComponentInterface
{
    public $sortColumn = 'created_at';

    #[Computed]
    public function queryRefresh()
    {
        $query = TransshipmentCard::query()
            ->with('createdBy', 'storageYard', 'units');
        return $this->tableRefresh($query);
    }

    #[On('setTcNumberInTransshipmentCardsTable')]
    public function setCardNumber(string $cardNumber) : void
    {
        $this->searchTerm['text']['tc_number'] = $cardNumber;
    }

    public function render()
    {
        return view('livewire.transshipment-cards.transshipment-cards-table', [
            'data' => $this->queryRefresh(),
            'statusEnum' => TransshipmentCardStatusenum::class,
            'relationEnum' => OperationRelationEnum::class,
        ]);
    }
}
