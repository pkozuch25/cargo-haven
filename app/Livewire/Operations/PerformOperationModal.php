<?php

namespace App\Livewire\Operations;

use App\Livewire\ModalComponent;
use App\Models\DispositionUnit;
use Livewire\Attributes\On;

class PerformOperationModal extends ModalComponent
{
    public $title;

    #[On('performOperationModal')]
    public function performOperationModal(DispositionUnit $operation)
    {
        $this->title = __('Perform operation - ') . $operation->disu_container_number;
    }

    public function render()
    {
        return view('livewire.operations.perform-operation-modal');
    }
}
