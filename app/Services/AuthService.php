<?php

namespace App\Services;

use App\Models\RegistrationRequest;

class AuthService
{
    public function createNewRegistrationTicket(string $email, string $firstLastName) : void
    {
        RegistrationRequest::create([
            'rr_email' => $email,
            'rr_first_last_name' => $firstLastName,
        ]);

        // todo email do grupy adminów o nowym zgłoszeniu rejestracji
    }
}
