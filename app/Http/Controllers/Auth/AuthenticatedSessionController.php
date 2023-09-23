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
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        //validate the request
        $validate = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        //attempt to authenticate the user
        if (Auth::attempt($validate)) {
            $request->session()->regenerate();
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return back()->with('status', "Authentication failed.")->withInput($request->only('email'));

        //return redirect()->intended(RouteServiceProvider::HOME);
        //if ($request->authenticate()) {
        //    $request->session()->regenerate();
        //    return redirect()->intended(RouteServiceProvider::HOME);
        //}

        // Authentication failed, return a response with an error status
        //return redirect()->back()->withInput()->with(['status', 'Authentication failed.']);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
