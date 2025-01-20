<?php

namespace App\Livewire\RegistrationRequests;

use App\Interfaces\TableComponentInterface;
use App\Livewire\TableComponent;
use App\Models\RegistrationRequest;
use Livewire\Attributes\Computed;

class RegistrationRequestsTable extends TableComponent implements TableComponentInterface
{
    public $sortColumn = 'created_at', $selectedStatus = [0,1,2];

    public function mount()
    {
        // dd(Auth::user()->hasRole('operator'));
    }

    #[Computed]
    public function queryRefresh()
    {
        $registrationRequests = RegistrationRequest::query()
            ->leftJoin('users', 'users.id', '=', 'registration_requests.rr_user_id')
            ->select('registration_requests.*', 'users.name', 'users.email')
            ->whereIn('rr_status', $this->selectedStatus);
        
        return $this->tableRefresh($registrationRequests);
    }

    public function render()
    {
        return view('livewire.registration-requests.registration-requests-table');
    }
}
