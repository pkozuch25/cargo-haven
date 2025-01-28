<?php

namespace App\Livewire\RegistrationRequests;

use Livewire\Attributes\On;
use App\Livewire\TableComponent;
use Livewire\Attributes\Computed;
use App\Models\RegistrationRequest;
use App\Interfaces\TableComponentInterface;
use App\Enums\RegistrationRequestStatusEnum;

class RegistrationRequestsTable extends TableComponent implements TableComponentInterface
{
    public $sortColumn = 'created_at', $selectedStatus = [RegistrationRequestStatusEnum::PENDING->value];

    #[On('refreshRRTable')]
    #[Computed]
    public function queryRefresh()
    {
        $registrationRequests = RegistrationRequest::query()
            ->leftJoin('users', 'users.id', '=', 'registration_requests.rr_user_id')
            ->select('registration_requests.*', 'users.name', 'users.email')
            ->whereIn('rr_status', $this->selectedStatus == [] ? RegistrationRequestStatusEnum::cases() : $this->selectedStatus);

        return $this->tableRefresh($registrationRequests);
    }

    public function render()
    {
        return view('livewire.registration-requests.registration-requests-table', ['data' => $this->queryRefresh()]);
    }
}
