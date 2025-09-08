<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PremiumController extends Controller
{
    public function show()
    {
        return view('theme.premium.show');
    }
}
