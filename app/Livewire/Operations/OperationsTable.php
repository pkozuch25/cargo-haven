<?php

namespace App\Livewire\Operations;

use Livewire\Attributes\On;
use App\Models\DispositionUnit;
use App\Livewire\TableComponent;
use Livewire\Attributes\Computed;
use App\Interfaces\TableComponentInterface;
use Illuminate\Support\Facades\Auth;

class OperationsTable extends TableComponent implements TableComponentInterface
{
    public $sortColumn = 'created_at';

    #[On('refreshOperations')]
    #[Computed]
    public function queryRefresh()
    {
        return $this->tableRefresh(DispositionUnit::with('disposition')->forOperator(Auth::id()));
    }

    public function render()
    {
        return view('livewire.operations.operations-table', ['data' => $this->queryRefresh()]);
    }
}
