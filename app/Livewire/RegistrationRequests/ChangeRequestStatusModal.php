<?php

namespace App\Livewire\RegistrationRequests;

use App\Models\User;
use Livewire\Attributes\On;
use App\Enums\UserStatusEnum;
use App\Livewire\ModalComponent;
use App\Models\RegistrationRequest;
use Illuminate\Support\Facades\Mail;
use App\Enums\RegistrationRequestStatusEnum;
use App\Mail\NewRegistrationRequestNotification;
use App\Mail\UserChangedStatusNotification;

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

        $this->notifyUserOfStatusChange($user);
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

    
    private function notifyUserOfStatusChange(User $user): void
    {
        Mail::to($user->email)->send(new UserChangedStatusNotification($this->rr));
    }

    public function render()
    {
        return view('livewire.registration-requests.change-request-status-modal');
    }
}
