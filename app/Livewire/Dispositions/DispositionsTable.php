<?php

namespace App\Livewire\Dispositions;

use App\Enums\DispositionStatusEnum;
use App\Models\Disposition;
use App\Livewire\TableComponent;
use Livewire\Attributes\Computed;
use App\Interfaces\TableComponentInterface;

class DispositionsTable extends TableComponent implements TableComponentInterface
{
    public $sortColumn = 'dis_suggested_date';

    #[Computed]
    public function queryRefresh()
    {
        $query = Disposition::query()
            ->with('createdBy')
            ->withCount('units');

        return $this->tableRefresh($query);
    }

    public function render()
    {
        return view('livewire.dispositions.dispositions-table', ['data' => $this->queryRefresh()]);
    }
}
