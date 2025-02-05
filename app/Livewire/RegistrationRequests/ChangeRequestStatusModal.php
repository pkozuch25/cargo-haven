<?php

namespace App\Livewire\RegistrationRequests;

use App\Enums\RegistrationRequestStatusEnum;
use App\Enums\UserStatusEnum;
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
        
        $user = $this->rr->user()->first();
        $user->status = $this->determineUserStatus($this->requestStatus);
        $user->save();
        
        $this->closeModal();
        $this->dispatch('refreshRRTable');
        $this->dispatch('refreshSelect2', ['await' => true]);
    }

    private function determineUserStatus($status) : UserStatusEnum
    {
        if ($status == RegistrationRequestStatusEnum::APPROVED) {
            return UserStatusEnum::ACTIVE;
        } else if ($status == RegistrationRequestStatusEnum::REJECTED) {
            return UserStatusEnum::REJECTED;
        } else {
            return UserStatusEnum::INACTIVE;
        }

    }

    public function render()
    {
        return view('livewire.registration-requests.change-request-status-modal');
    }
}
