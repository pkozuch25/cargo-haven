<?php

namespace App\Livewire\Users;

use App\Livewire\TableComponent;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use App\Interfaces\TableComponentInterface;

class UsersTable extends TableComponent implements TableComponentInterface
{
    public $sortColumn = 'name', $perPage = 25;

    #[On('refreshUsersTable')]
    #[Computed]
    public function queryRefresh()
    {
        $users = User::query()
            ->select('users.*');
        return $this->tableRefresh($users);
    }

    public function render()
    {
        return view('livewire.users.users-table', ['data' => $this->queryRefresh()]);
    }
}
