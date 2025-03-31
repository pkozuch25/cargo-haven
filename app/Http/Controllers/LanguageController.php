<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function changeLanguage(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|in:pl,en',
        ]);

        $user = getAuthenticatedUserModel();
        $user->locale = $request->locale;
        $user->save();
        
        return redirect()->back()->with('success', __('Language changed successfully'));
    }
}
