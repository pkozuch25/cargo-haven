<?php

namespace App\Livewire\StorageYards;

use Livewire\Attributes\On;
use App\Livewire\TableComponent;
use Livewire\Attributes\Computed;
use App\Interfaces\TableComponentInterface;
use App\Models\StorageYard;

class StorageYardsTable extends TableComponent implements TableComponentInterface
{
    public $sortColumn = 'sy_name';

    #[On('refreshDispositionTable')]
    #[Computed]
    public function queryRefresh()
    {
        $query = StorageYard::query();

        return $this->tableRefresh($query);
    }

    public function render()
    {
        return view('livewire.storage-yards.storage-yards-table', ['data' => $this->queryRefresh()]);
    }
}
