<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class PasswordRecoveryController extends Controller
{
    /**
     * Mostrar formulario de solicitud de recuperación de contraseña
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Procesar solicitud de recuperación (enviar correo)
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Verificar si el usuario existe
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Usuario inexistente. La cuenta con este correo no fue encontrada.');
        }

        // Generar token único
        $token = Str::random(64);

        // Eliminar tokens anteriores para este email
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Guardar el nuevo token
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Enviar correo con el enlace de recuperación
        try {
            Mail::send('auth.emails.reset-password', [
                'user' => $user,
                'token' => $token,
                'resetLink' => route('password.reset.form', ['token' => $token, 'email' => $request->email]),
            ], function ($message) use ($request, $user) {
                $message->to($request->email)
                    ->subject('Recuperar Contraseña - CEOGestion');
            });

            return back()->with('success', 'Se ha enviado un correo de recuperación a ' . $request->email . '. Por favor revisa tu bandeja de entrada.');
        } catch (\Exception $e) {
            \Log::error('Error enviando correo de recuperación: ' . $e->getMessage());
            return back()->with('error', 'Error al enviar el correo. Por favor intenta más tarde.');
        }
    }

    /**
     * Mostrar formulario de restablecimiento de contraseña
     */
    public function showResetForm($token, $email)
    {
        // Verificar que el token sea válido y no haya expirado (24 horas)
        $passwordReset = DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$passwordReset) {
            return redirect(route('password.forgot'))->with('error', 'El enlace de recuperación es inválido o ha expirado.');
        }

        // Verificar que no haya pasado más de 24 horas
        if (now()->diffInHours($passwordReset->created_at) > 24) {
            DB::table('password_resets')->where('email', $email)->delete();
            return redirect(route('password.forgot'))->with('error', 'El enlace de recuperación ha expirado. Por favor solicita uno nuevo.');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Procesar restablecimiento de contraseña
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        // Verificar que el token sea válido
        $passwordReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset) {
            return back()->with('error', 'El token de recuperación es inválido.');
        }

        // Verificar que el token no haya expirado
        if (now()->diffInHours($passwordReset->created_at) > 24) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return back()->with('error', 'El enlace de recuperación ha expirado. Por favor solicita uno nuevo.');
        }

        // Buscar el usuario y actualizar su contraseña
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Usuario no encontrado.');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Eliminar el token después de usarlo
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect(route('login'))->with('success', 'Tu contraseña ha sido restablecida correctamente. Por favor inicia sesión con tu nueva contraseña.');
    }
}
