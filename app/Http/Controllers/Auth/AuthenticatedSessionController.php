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
        $avatarClasses = ['bg-green-lt', 'bg-red-lt', 'bg-yellow-lt', 'bg-primary-lt', 'bg-purple-lt', 'bg-blue-lt', 'bg-azure-lt', 'bg-indigo-lt', 'bg-pink-lt', 'bg-orange-lt', 'bg-lime-lt', 'bg-teal-lt', 'bg-cyan-lt'];
        $randomClass = $avatarClasses[array_rand($avatarClasses)]; // Clase aleatoria

        // Guardar la clase en la sesión
        session(['avatar_class' => $randomClass]);

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
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
