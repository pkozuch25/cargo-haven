<?php

namespace App\Http\Controllers;

class DispositionsController extends Controller
{

    public function index()
    {
        if (!can('view_dispositions')) {
            return redirect()->route('deposits.index');
        }

        return view('pages.dispositions.dispositions-index');
    }
}
