<?php

namespace App\Http\Controllers;

class DepositsController extends Controller
{

    public function index()
    {
        if (!can('view_deposits')) {
            return redirect()->route('deposits.index');
        }

        return view('pages.deposits.deposits-index');
    }
}
