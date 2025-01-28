<?php

namespace App\Livewire\Dispositions;

use App\Models\Disposition;
use App\Livewire\TableComponent;
use Livewire\Attributes\Computed;
use App\Interfaces\TableComponentInterface;

class DispositionsTable extends TableComponent implements TableComponentInterface
{
    public function mount()
    {

    }

    #[Computed]
    public function queryRefresh()
    {
        $query = Disposition::withCount('units');

        return $this->tableRefresh($query);
    }

    public function render()
    {
        return view('livewire.dispositions.dispositions-table', ['data' => $this->queryRefresh()]);
    }
}
