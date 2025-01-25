<?php

namespace App\Livewire\Dispositions;

use App\Livewire\MainComponent;

class DispositionsTable extends MainComponent
{
    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.dispositions.dispositions-table');
    }
}
