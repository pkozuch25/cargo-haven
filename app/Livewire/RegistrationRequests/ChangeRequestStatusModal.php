<?php

namespace App\Livewire\RegistrationRequests;

use Livewire\Attributes\On;
use App\Livewire\ModalComponent;
use App\Models\RegistrationRequest;

class ChangeRequestStatusModal extends ModalComponent
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
        $this->closeModal();
        $this->dispatch('refreshRRTable');
        $this->dispatch('refreshSelect2', ['await' => true]);
    }

    public function render()
    {
        return view('livewire.registration-requests.change-request-status-modal');
    }
}
