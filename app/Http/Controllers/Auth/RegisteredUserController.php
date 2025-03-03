<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        (new AuthService)->createNewRegistrationTicket($request->email, $request->name, $request->password);

        return redirect(route('login', absolute: false));
    }

    public function getOperatorsToSelect2(Request $request) 
    {
        $request->validate(
            [
                'search' => ['string', 'max:255', 'nullable'],
            ]
        );

        return User::operator()
            ->where('name', 'like', "%$request->search%")
            ->orderBy('name', 'asc')
            ->get(['id', 'name as text'])
            ->toArray();
    }
}
