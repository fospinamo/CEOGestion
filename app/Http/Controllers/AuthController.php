<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login - OPTIMIZADO
     * Solo carga empresa y tema si son necesarios para la vista
     */
    public function showLogin()
    {
        // Usar cache para evitar queries repetidas
        $empresa = cache()->remember('login_empresa', 3600, function () {
            return Empresa::where('estado', true)->first() ?? Empresa::first();
        });
        
        $theme = null;
        if ($empresa) {
            $theme = cache()->remember("login_theme_{$empresa->id}", 3600, function () use ($empresa) {
                return $empresa->themeSetting()->first();
            });
        }
        
        return view('auth.login', [
            'empresa' => $empresa,
            'theme' => $theme,
        ]);
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __('auth.failed')]);
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
