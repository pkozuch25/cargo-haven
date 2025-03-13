<?php

namespace App\Http\Controllers;

class OperationsController extends Controller
{

    public function index()
    {
        if (!can('view_operations')) {
            return redirect()->route('deposits.index');
        }

        return view('pages.operations.operations-index');
    }
}
