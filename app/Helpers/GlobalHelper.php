<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Access\Gate;

function getAuthenticatedUserModel() : User
{
    /** @var \app\Models\User $user */
    $user = Auth::user();

    return $user;
}

function can($abilities, $arguments = [])
{
    return app(Gate::class)->forUser(getAuthenticatedUserModel())->check($abilities, $arguments); 
}
