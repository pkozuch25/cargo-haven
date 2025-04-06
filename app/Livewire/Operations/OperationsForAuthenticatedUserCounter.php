<?php

namespace App\Livewire\Operations;

use Livewire\Component;
use App\Models\DispositionUnit;
use Livewire\Attributes\On;

class OperationsForAuthenticatedUserCounter extends Component
{
    public bool $responsive;

    public function mount(bool $responsive = false)
    {
        $this->responsive = $responsive;
    }

    #[On('refreshOperationsCounter')]
    public function getOperationsBadgeCount()
    {
        return DispositionUnit::forOperator(getAuthenticatedUserModel()->id)->count();
    }

    public function render()
    {
        return view('livewire.operations.operations-for-authenticated-user-counter');
    }
}
