<?php

namespace App\Livewire\Dashboard;

use App\Livewire\MainComponent;

class DashboardMain extends MainComponent
{

    public function test()
    {
        $this->sweetAlert('success', 'Test', 900);
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-main');
    }
}
