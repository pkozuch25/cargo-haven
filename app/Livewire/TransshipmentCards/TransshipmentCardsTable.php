<?php

namespace App\Livewire\TransshipmentCards;

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

    public function render()
    {
        return view('livewire.transshipment-cards.transshipment-cards-table', [
            'data' => $this->queryRefresh(),
            'statusEnum' => TransshipmentCardStatusenum::class,
            'relationEnum' => OperationRelationEnum::class,
        ]);
    }
}
