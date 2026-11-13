<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Ingresa tu correo.',
            'email.email' => 'El correo no es válido.',
            'password.required' => 'Ingresa tu contraseña.',
        ]);

        // Solo usuarios activos pueden ingresar.
        if (Auth::attempt($credenciales + ['estado' => true], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'Credenciales incorrectas o usuario desactivado.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
