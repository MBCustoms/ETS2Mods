<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function switch($locale): RedirectResponse
    {
        if (in_array($locale, ['en', 'es', 'de', 'fr'])) { // Allowed locales
            session(['locale' => $locale]);
        }

        return back();
    }
}
