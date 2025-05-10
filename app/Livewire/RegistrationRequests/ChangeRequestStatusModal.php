<?php

namespace App\Livewire\RegistrationRequests;

use App\Models\User;
use Livewire\Attributes\On;
use App\Enums\UserStatusEnum;
use App\Livewire\ModalComponent;
use Spatie\Permission\Models\Role;
use App\Models\RegistrationRequest;
use App\Models\StorageYard;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserChangedStatusNotification;
use App\Enums\RegistrationRequestStatusEnum;

class ChangeRequestStatusModal extends ModalComponent
{
    public $requestStatus, $registrationRequest, $selectedRoles, $availableRoles, $user;
    public $availableStorageYards = [];
    public $selectedStorageYards = [];

    protected $rules = [
        'requestStatus' => 'required',
        'selectedRoles' => 'required|array|min:1',
        'selectedStorageYards' => 'nullable|array',
    ];

    #[On('openChangeStatusModal')]
    public function openChangeStatusModal(RegistrationRequest $registrationRequest)
    {
        $this->registrationRequest = $registrationRequest;
        $this->requestStatus = $registrationRequest->rr_status;
        $this->availableRoles = Role::all();
        $this->user = $this->registrationRequest->user()->first();
        $this->availableStorageYards = StorageYard::all();

        $this->selectedRoles = $this->user->roles->pluck('id')->toArray();
        $this->selectedStorageYards = $this->user->storageYards->pluck('sy_id')->toArray();

        $this->dispatch('iniYardSelect2');
    }

    public function saveStatus()
    {
        $this->validate();

        $this->registrationRequest->rr_status = $this->requestStatus;
        $this->registrationRequest->save();

        $this->user->status = $this->determineUserStatus($this->requestStatus);
        $this->user->save();

        $roleNames = Role::whereIn('id', $this->selectedRoles)->pluck('name')->toArray();
        $this->user->syncRoles($roleNames);

        $this->user->storageYards()->sync($this->selectedStorageYards);

        $this->closeModal();
        $this->dispatch('refreshRegistrationRequestTable');
        $this->dispatch('refreshSelect2', ['await' => true]);

        $this->notifyUserOfStatusChange($this->user);
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
        Mail::to($user->email)->send(new UserChangedStatusNotification($this->registrationRequest));
    }

    public function render()
    {
        return view('livewire.registration-requests.change-request-status-modal');
    }
}
