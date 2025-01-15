<?php

namespace App\Livewire;

use App\Livewire\MainComponent;


class ModalComponent extends MainComponent 
{
    public function closeModal(bool $reset = false)
    {
        if ($reset) {
            $this->reset();
            $this->resetErrorBag();
        }
        $this->dispatch('closeModal');
    }
}
