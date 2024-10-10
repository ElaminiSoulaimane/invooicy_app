<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch($lang)
    {
        if (in_array($lang, ['fr', 'ar'])) {
            App::setLocale($lang);
            Session::put('locale', $lang);
        } else {
            App::setLocale(Session::get('locale', config('app.locale')));
        }

        return redirect()->back();
    }
}
