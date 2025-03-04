<?php

namespace App\Livewire\Dispositions\DispositionUnits;

use App\Models\Disposition;
use Livewire\Component;

class DispositionUnitsFormTable extends Component
{
    public $disposition;

    public function mount(Disposition $disposition)
    {
        $this->disposition = $disposition;
    }

    public function render()
    {
        return view('livewire.dispositions.disposition-units.disposition-units-form-table');
    }
}
