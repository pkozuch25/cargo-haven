<?php

namespace App\Livewire\Dispositions;

use App\Models\User;
use App\Models\Disposition;
use App\Models\StorageYard;
use Livewire\Attributes\On;
use App\Livewire\TableComponent;
use Livewire\Attributes\Computed;
use App\Interfaces\TableComponentInterface;

class DispositionsTable extends TableComponent implements TableComponentInterface
{
    public $sortColumn = 'dis_suggested_date', $allYards = [], $allUsers = [];

    public function mount()
    {
        $this->allYards = StorageYard::all();
        $this->allUsers = User::all();
    }

    #[On('refreshDispositionTable')]
    #[Computed]
    public function queryRefresh()
    {
        $query = Disposition::query()
            ->with('createdBy', 'storageYard')
            ->withCount('units');

        return $this->tableRefresh($query);
    }

    public function render()
    {
        return view('livewire.dispositions.dispositions-table', ['data' => $this->queryRefresh()]);
    }
}
