<?php

namespace App\Livewire\Profile;

use App\Livewire\ModalComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class DeleteUserModal extends ModalComponent
{
    public $password;

    protected $rules = [
        'password' => ['required', 'current_password'],
    ];

    public function deleteUser()
    {
        $this->addError('password', 'aaa');
        $this->validate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        Auth::logout();

        $user->delete();

        $request = request();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function render()
    {
        return view('livewire.profile.delete-user-modal');
    }
}
