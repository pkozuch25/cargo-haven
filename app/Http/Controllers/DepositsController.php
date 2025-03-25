<?php

namespace App\Http\Controllers;

class DepositsController extends Controller
{

    public function index()
    {
        return view('pages.deposits.deposits-index');
    }
}
