<?php

namespace App\Http\Controllers;

class TransshipmentCardsController extends Controller
{

    public function index()
    {
        if (!can('view_transshipment_cards')) {
            return redirect()->route('deposits.index');
        }

        return view('pages.transshipment-cards.transshipment-cards-index');
    }
}
