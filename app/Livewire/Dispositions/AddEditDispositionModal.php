<?php

namespace App\Livewire\Dispositions;

use App\Livewire\MainComponent;

class AddEditDispositionModal extends MainComponent
{
    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.dispositions.add-edit-disposition-modal');
    }
}
