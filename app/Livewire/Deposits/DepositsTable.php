<?php

namespace App\Livewire\Deposits;

use App\Livewire\MainComponent;
use Illuminate\Support\Facades\Auth;

class DepositsTable extends MainComponent
{
    public function mount()
{
        // dd(Auth::user()->hasRole('operator'));
    }

    public function render()
    {
        return view('livewire.deposits.deposits-table');
    }
}
