<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    public function show()
    {
        return view('auth.force-change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(6), 'different:current_password'],
        ], [
            'current_password.current_password' => 'A senha atual informada está incorreta.',
            'password.min'                     => 'A nova senha deve ter pelo menos 6 caracteres.',
            'password.confirmed'               => 'A confirmação da nova senha não confere.',
            'password.different'               => 'A nova senha deve ser diferente da senha atual temporária.',
        ]);

        $user = auth()->user() ?? $request->user();

        $user->update([
            'password'             => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->route('dashboard')->with('success', 'Sua senha foi alterada com sucesso! Bem-vindo(a) ao sistema Etec SAM.');
    }
}

