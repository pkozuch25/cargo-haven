<?php

namespace App\Http\Controllers;

class StorageYardsController extends Controller
{

    public function index()
    {
        if (!can('view_storage_yards')) {
            return redirect()->route('dashboard');
        }

        return view('pages.storage-yards.storage-yards-index');
    }
}
