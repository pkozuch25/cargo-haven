<?php

namespace App\Services;

use App\Enums\UserStatusEnum;
use App\Models\User;
use App\Models\RegistrationRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthService
{
    public function createNewRegistrationTicket(string $email, string $firstLastName, string $password) : void
    {
        $user = User::create([
            'name' => $firstLastName,
            'email' => $email,
            'status' => UserStatusEnum::INACTIVE,
            'password' => Hash::make($password),
        ]);

        RegistrationRequest::create([
            'rr_user_id' => $user->id
        ]);

        session(['displayMessage' => true]);

        event(new Registered($user));

        // todo email do grupy adminów o nowym zgłoszeniu rejestracji
    }
}
