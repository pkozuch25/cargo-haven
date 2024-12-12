<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DepositsController extends Controller
{

    // public function edit(Request $request): View
    // {
    //     return view('deposits.deposits-index');
    // }

    public function index(): View
    {
        return view('deposits.deposits-index');
    }
}
