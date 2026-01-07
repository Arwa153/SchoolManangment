<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request)
    {
        $language = $request->input('language');
        
        if (array_key_exists($language, config('languages.supported'))) {
            Session::put('language', $language);
        }

        return redirect()->back();
    }
}