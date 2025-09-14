<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PremiumController extends Controller
{
    public function show()
    {
        // هنا بإمكانك تمرر خطة أو سعر أو حالة Cashier إن أردت
        return view('theme.premium.show');
    }
}
