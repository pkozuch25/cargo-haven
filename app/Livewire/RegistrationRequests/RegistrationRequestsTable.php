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
    public $sortColumn = 'created_at', $selectedStatus = [], $perPage = 25;

    public function mount()
    {
        $this->searchTerm['selectMultiple']['rr_Status'] = [RegistrationRequestStatusEnum::PENDING->value];
        $this->selectedStatus = [RegistrationRequestStatusEnum::PENDING->value];
    }

    #[On('refreshRRTable')]
    #[Computed]
    public function queryRefresh()
    {
        dd(RegistrationRequest::all());
        $registrationRequests = RegistrationRequest::query()
            ->leftJoin('users', 'users.id', '=', 'registration_requests.rr_user_id')
            ->select('registration_requests.*', 'users.name', 'users.email');

        return $this->tableRefresh($registrationRequests);
    }

    public function render()
    {
        return view('livewire.registration-requests.registration-requests-table', ['data' => $this->queryRefresh()]);
    }
}
