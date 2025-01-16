<?php

namespace App\Http\Controllers;

class PermissionsController extends Controller
{

    public function index()
    {
        if (!can('manage_permissions')) {
            return redirect()->route('dashboard');
        }

        return view('pages.permissions.permissions-index');
    }
}
