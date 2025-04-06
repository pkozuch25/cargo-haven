<?php

namespace App\Livewire\RegistrationRequests;

use App\Enums\RegistrationRequestStatusEnum;
use App\Models\RegistrationRequest;
use Livewire\Component;

class PendingRegistrationRequestsCounter extends Component
{
    public bool $responsive;

    public function mount(bool $responsive = false)
    {
        $this->responsive = $responsive;
    }

    public function getRegistrationRequestsBadgeCount()
    {
        return RegistrationRequest::where('rr_status', RegistrationRequestStatusEnum::PENDING)->count();
    }

    public function render()
    {
        return view('livewire.registration-requests.pending-registration-requests-counter');
    }
}
