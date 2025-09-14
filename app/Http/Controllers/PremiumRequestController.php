<?php
// app/Http/Controllers/PremiumRequestController.php
namespace App\Http\Controllers;

use App\Models\PremiumRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PremiumRequestController extends Controller
{
    public function store(Request $req)
    {
        $req->validate([
            'provider' => 'required|string',
            'txid'     => 'required|string|max:191',
            'email'    => 'required|email',
            'note'     => 'nullable|string',
            'reference'=> 'required|string',
        ]);

        $pr = PremiumRequest::create([
            'user_id'  => Auth::id(),
            'provider' => $req->provider,
            'txid'     => $req->txid,
            'email'    => $req->email,
            'note'     => $req->note,
            'reference'=> $req->reference,
        ]);

        return response()->json(['success'=>true,'request'=>$pr]);
    }
}
