<?php

namespace App\Http\Controllers;

class PermissionsController extends Controller
{

    public function index()
    {
        if (!can('view_permissions')) {
            return redirect()->route('dashboard');
        }

        return view('pages.deposits.deposits-index');
    }
}
