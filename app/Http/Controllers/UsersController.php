<?php

namespace App\Http\Controllers;

class UsersController extends Controller
{

    public function index()
    {
        if (!can('view_users')) {
            return redirect()->route('deposits.index');
        }

        return view('pages.users.users-index');
    }
}
