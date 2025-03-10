<?php

namespace App\Http\Controllers;

class RegistrationRequestController extends Controller
{

    public function index()
    {
        if (!getAuthenticatedUserModel()->isAdmin()) {
            return redirect()->route('deposits.index');
        }

        return view('pages.registration-requests.registration-requests-index');
    }
}
