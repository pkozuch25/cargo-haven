<?php

namespace App\Livewire\RegistrationRequests;

use App\Enums\RegistrationRequestStatusEnum;
use App\Interfaces\TableComponentInterface;
use App\Livewire\TableComponent;
use App\Models\RegistrationRequest;
use Livewire\Attributes\Computed;

class RegistrationRequestsTable extends TableComponent implements TableComponentInterface
{
    public $sortColumn = 'created_at', $selectedStatus = [];

    public function mount()
    {
        // dd(Auth::user()->hasRole('operator'));
    }

    public function updated()
    {
        $this->dispatch('refreshSelect2');
    }

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
