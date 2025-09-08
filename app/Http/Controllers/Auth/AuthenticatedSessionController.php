<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('theme.partials.login');
    }

    /**
     * Handle an incoming authentication request.
     */
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = $request->user();

    $isAdmin = (($user->role ?? null) === 'admin') || (bool)($user->is_admin ?? false);

    $intended = $request->session()->get('url.intended');
    if (!$isAdmin && is_string($intended)) {
        $path = parse_url($intended, PHP_URL_PATH) ?? '';
        if (str_starts_with($path, '/admin')) {
            $request->session()->forget('url.intended');
        }
    }

    return redirect()->intended($isAdmin ? route('admin.dashboard') : route('theme.index'));
}



    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
