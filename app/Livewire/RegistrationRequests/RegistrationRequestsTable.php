<?php

namespace App\Livewire\RegistrationRequests;

use App\Livewire\MainComponent;
use Illuminate\Support\Facades\Auth;

class RegistrationRequestsTable extends MainComponent
{
    public function mount()
{
        // dd(Auth::user()->hasRole('operator'));
    }

    public function render()
    {
        return view('livewire.registration-requests.registration-requests-table');
    }
}
