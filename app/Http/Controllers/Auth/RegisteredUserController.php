<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $messages = [
            'email.unique' => 'O email fornecido já está em uso.',
            'user_type.required' => "O campo type de ve ser fornecido.",
            'user_type.in' => "Deve ser fornecido apenas os valores 'admin' para usuário do tipo administrador  ou 'user' apara o usuário do tipo comum!"
        ];
        $validate = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'user_type' => ['required', 'string','in:admin,user'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], $messages);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'user_type' => $request->user_type,

            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Usuário registrado com sucesso!'], 200);
    }
}
