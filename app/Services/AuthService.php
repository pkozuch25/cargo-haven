<?php

namespace App\Services;

use App\Models\User;
use App\Enums\UserStatusEnum;
use App\Models\RegistrationRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use App\Mail\NewRegistrationRequestNotification;

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

        $this->notifyAdminsAboutNewRegistration($user);
    }

    private function notifyAdminsAboutNewRegistration(User $newUser): void
    {
        $adminUsers = User::admin()->get();
        $adminEmails = $adminUsers->pluck('email')->toArray();
        
        foreach ($adminEmails as $email) {
            Mail::to($email)->send(new NewRegistrationRequestNotification($newUser));
        }
    }
}
