<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\StorageYard;
use Livewire\Attributes\On;
use App\Livewire\ModalComponent;
use Spatie\Permission\Models\Role;

class ManageUserModal extends ModalComponent
{
    public $user;
    public $selectedRoles = [];
    public $availableRoles = [];
    public $availableStorageYards = [];
    public $selectedStorageYards = [];

    protected $rules = [
        'selectedRoles' => 'required|array|min:1',
        'selectedStorageYards' => 'nullable|array',
    ];

    #[On('openManageUserModal')]
    public function openManageUserModal(User $user)
    {
        $this->user = $user;
        $this->availableRoles = Role::all();
        $this->selectedRoles = $user->roles->pluck('id')->toArray();
        
        $this->availableStorageYards = StorageYard::all();
        $this->selectedStorageYards = $this->user->storageYards->pluck('sy_id')->toArray();
    }

    public function saveRoles()
    {
        $this->validate();
        $roleNames = Role::whereIn('id', $this->selectedRoles)->pluck('name')->toArray();

        $this->user->syncRoles($roleNames);
        $this->user->storageYards()->sync($this->selectedStorageYards);

        $this->closeModal();
        $this->dispatch('refreshUsersTable');
        $this->sweetAlert('success', __('Roles updated successfully'));
    }

    public function render()
    {
        return view('livewire.users.manage-user-modal');
    }
}
