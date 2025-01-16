<?php

namespace App\Livewire\Permissions;

use App\Livewire\MainComponent;

class PermissionsTable extends MainComponent
{
    public function mount()
    {
        // dd(getAuthenticatedUserModel()->isAdmin());
    }

    public function render()
    {
        return view('livewire.permissions.permissions-table');
    }
}
