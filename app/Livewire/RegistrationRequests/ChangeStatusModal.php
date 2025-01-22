<?php

namespace App\Livewire\RegistrationRequests;

use Livewire\Attributes\On;
use App\Livewire\ModalComponent;
use App\Models\RegistrationRequest;

class ChangeStatusModal extends ModalComponent
{
    public $requestStatus, $rr;
    protected $rules = ['requestStatus' => 'required'];

    #[On('openChangeStatusModal')]
    public function openChangeStatusModal(RegistrationRequest $rr)
    {
        $this->rr = $rr;
    }

    public function saveStatus()
    {
        $this->validate();

        $this->rr->rr_status = $this->requestStatus;
        $this->rr->save();
        $this->dispatch('refreshRRTable');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.registration-requests.change-status-modal');
    }
}
