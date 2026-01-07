<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLanguage
{
    public function handle(Request $request, Closure $next)
    {
        $language = Session::get('language', config('languages.default'));
        
        if (array_key_exists($language, config('languages.supported'))) {
            App::setLocale($language);
        }

        return $next($request);
    }
}